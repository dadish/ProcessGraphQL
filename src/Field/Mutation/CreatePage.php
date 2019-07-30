<?php namespace ProcessWire\GraphQL\Field\Mutation;

use ProcessWire\Template;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\PageType;
use GraphQL\Type\Definition\InputObjectType;
use ProcessWire\GraphQL\Utils;

class CreatePage
{
  use CacheTrait;
  public static function field(Template $template)
  {
    return self::cache('default', function () use ($template) {
      return [
        'name' => self::name($template),
        'description' => self::description($template),
        'type' => PageType::type($template),
        'args' => [
          'page' => self::inputType($template),
        ],
        'resolve' => function ($value, $args) use ($template) {
          return self::resolve($value, $args, $template);
        }
      ];
    });
  }

  public static function name($template)
  {
    $typeName = PageType::normalizeName($template->name);
    return "create_{$typeName}";
  }

  public function getDescription(Template $template)
  {
    return "Allows you to create Pages with template `{$template->name}`.";
  }

  public static function inputType(Template $template)
  {
    return self::cache('defaultInput', function () use ($template) {
      return new InputObjectType([
        'name' => ucfirst(PageType::normalizeName($template->name)) . 'CreateInput',
        'description' => "CreateInputType for pages with template {$template->name}.",
        'fields' => self::getInputFields($template),
      ]);
    });
  }

  public static function getInputFields(Template $template)
  {
    $fields = [];

    // parent
    $fields[] = [
      'name' => 'parent',
      'type' => new NonNullType(new StringType()),
      'description' => 'Id or the path of the parent page.',
    ];

    // name
    $fields[] = [
      'name' => 'name',
      'type' => new NonNullType(new StringType()),
      'description' => 'ProcessWire page name.',
    ];

    // the list of input fields we do not 
    // support for now
    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = Utils::moduleConfig()->legalFields->implode('|', 'name');
    foreach ($template->fields->find("name=$legalFieldsName") as $field) {

      // get the field's GraphQL input class
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) {
        continue;
      }

      $f = Utils::pwFieldToGraphqlClass($field);
      if (!is_null($f)) {
        $fields[] = $f::inputType($field);
      }
    }

    return $fields;
  }

  public static function resolve($value, $args, $template)
  {
    // prepare neccessary variables
    $pages = Utils::pages();
    $sanitizer = Utils::sanitizer();
    $fields = Utils::fields();
    $values = (array) $args['page'];

    /*********************************************\
     *                                           *
     * Don't ever take sides against the family! *
     *                                           *
    \*********************************************/
    // can new pages be created for this template?
    if ($template->noParents === 1) {
      throw new ValidationException("No new pages can be created for the template `{$template->name}`.");
    }

    // if there could be only one page is there already a page with this template
    if ($template->noParents === -1 && !$pages->get("template={$template}") instanceof NullPage) {
      throw new ValidationException("Only one page with template `{$template->name}` can be created.");
    }

    // find the parent
    $parentSelector = $values['parent'];
    $parent = $pages->get($sanitizer->selectorValue($parentSelector));

    // if no parent then no good. No child should born without a parent!
    if (!$parent || $parent instanceof NullPage) {
      throw new ValidationException("Could not find the parent: '$parentSelector'.");
    }

    // make sure user is allowed to add children to this parent
    $legalAddTemplates = Utils::moduleConfig()->legalAddTemplates;
    if (!$legalAddTemplates->has($parent->template)) {
      throw new ValidationException("You are not allowed to add children to the parent: '$parentSelector'.");
    }

    // make sure parent is allowed as a parent for this page
    $parentTemplates = $template->parentTemplates;
    if (count($parentTemplates) && !in_array($parent->template->id, $parentTemplates)) {
      throw new ValidationException("`parent` is not allowed as a parent.");
    }

    // make sure parent is allowed to have children
    if ($parent->template->noChildren === 1) {
      throw new ValidationException("`parent` is not allowed to have children.");
    }

    // make sure the page is allowed as a child for parent
    $childTemplates = $parent->template->childTemplates;
    if (count($childTemplates) && !in_array($template->id, $childTemplates)) {
      throw new ValidationException("not allowed to be a child for `parent`.");
    }

    // check if the name is valid
    $name = $sanitizer->pageName($values['name']);
    if (!$name) {
      throw new ValidationException('value for `name` field is invalid,');
    }
    // find out if the name is taken
    $taken = $pages->find("parent=$parent, name=$name")->count();
    if ($taken) {
      throw new ValidationException('`name` is already taken.');
    }

    // create the page
    $p = new Page();  
    $p->of(false);
    $p->template = $template;  
    $p->parent = $parent;
    $p->name = $name;

    // set the values from client
    unset($values['parent']);
    unset($values['name']);

    // update the values from client
    foreach ($values as $fieldName => $value) {
      $field = $fields->get($fieldName);
      
      // ignore if field cannot be found
      if (!$field instanceof Field) {
        continue;
      }

      // set value of a field
      $f = Utils::pwFieldToGraphqlClass($field);
      if (!is_null($f)) {
        $f::setValue($p, $field, $value);
      }
    }

    // save the page to db
    if ($p->save()) {
      return $pages->get("$p");
    }

    // If we did not return till now then no good!
    throw new ResolveException("Could not create page `$name` with template `{$template->name}`");
  }
}

