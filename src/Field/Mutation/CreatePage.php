<?php namespace ProcessWire\GraphQL\Field\Mutation;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use ProcessWire\Template;
use ProcessWire\Page;
use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Error\ValidationError;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\PageType;

class CreatePage
{
  use CacheTrait;
  public static function field(Template $template)
  {
    return self::cache('CreatePage--' . PageType::getTemplateCacheKey($template), function () use ($template) {
      return [
        'name' => self::name($template),
        'description' => self::description($template),
        'type' => PageType::type($template),
        'args' => [
          'page' => Type::nonNull(self::inputType($template)),
        ],
        'resolve' => function ($value, $args) use ($template) {
          return self::resolve($value, $args, $template);
        }
      ];
    });
  }

  public static function name($template)
  {
    $typeName = ucfirst(PageType::normalizeName($template->name));
    return "create{$typeName}";
  }

  public static function description(Template $template)
  {
    return "Allows you to create Pages with template `{$template->name}`.";
  }

  public static function inputType(Template $template)
  {
    return self::cache('CreateInputType--' . PageType::getTemplateCacheKey($template), function () use ($template) {
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
      'type' => Type::nonNull(Type::string()),
      'description' => 'Id or the path of the parent page.',
    ];

    // name
    $fields[] = [
      'name' => 'name',
      'type' => Type::nonNull(Type::string()),
      'description' => 'ProcessWire page name.',
    ];

    // the list of input fields we do not 
    // support for now
    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = implode('|', Utils::moduleConfig()->legalFields);
    foreach ($template->fields->find("name=$legalFieldsName") as $field) {

      // get the field's GraphQL input class
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) {
        continue;
      }

      $f = Utils::pwFieldToGraphqlClass($field);
      if (!is_null($f)) {
        $fields[] = $f::inputField($field);
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
      throw new ValidationError("No new pages can be created for the template `{$template->name}`.");
    }

    // if there could be only one page is there already a page with this template
    if ($template->noParents === -1 && !$pages->get("template={$template}") instanceof NullPage) {
      throw new ValidationError("Only one page with template `{$template->name}` can be created.");
    }

    // find the parent
    $parentSelector = $values['parent'];
    $parent = $pages->get($sanitizer->selectorValue($parentSelector));

    // if no parent then no good. No child should born without a parent!
    if (!$parent || $parent instanceof NullPage) {
      throw new ValidationError("Could not find the parent: '$parentSelector'.");
    }

    // make sure user is allowed to add children to this parent
    $legalAddTemplates = Utils::moduleConfig()->legalAddTemplates;
    if (!$legalAddTemplates->has($parent->template)) {
      throw new ValidationError("You are not allowed to add children to the parent: '$parentSelector'.");
    }

    // make sure parent is allowed as a parent for this page
    $parentTemplates = $template->parentTemplates;
    if (count($parentTemplates) && !in_array($parent->template->id, $parentTemplates)) {
      throw new ValidationError("`parent` is not allowed as a parent.");
    }

    // make sure parent is allowed to have children
    if ($parent->template->noChildren === 1) {
      throw new ValidationError("`parent` is not allowed to have children.");
    }

    // make sure the page is allowed as a child for parent
    $childTemplates = $parent->template->childTemplates;
    if (count($childTemplates) && !in_array($template->id, $childTemplates)) {
      throw new ValidationError("not allowed to be a child for `parent`.");
    }

    // check if the name is valid
    $name = $sanitizer->pageName($values['name']);
    if (!$name) {
      throw new ValidationError('value for `name` field is invalid,');
    }
    // find out if the name is taken
    $taken = $pages->find("parent=$parent, name=$name")->count();
    if ($taken) {
      throw new ValidationError('`name` is already taken.');
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

