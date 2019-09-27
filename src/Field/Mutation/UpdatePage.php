<?php namespace ProcessWire\GraphQL\Field\Mutation;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;
use ProcessWire\Template;
use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Error\ValidationError;
use ProcessWire\GraphQL\Type\PageType;

class UpdatePage
{
  public static function field(Template $template)
  {
    return [
      'name' => self::name($template),
      'description' => self::description($template),
      'type' => PageType::type($template),
      'args' => [
        'id' => Type::nonNull(Type::string()),
        'page' => Type::nonNull(self::inputType($template)),
      ],
      'resolve' => function ($value, $args) use ($template) {
        return self::resolve($value, $args, $template);
      }
    ];
  }

  public static function name($template)
  {
    return Utils::normalizeFieldName("update_{$template->name}");
  }

  public static function description(Template $template)
  {
    return "Allows you to update Pages with template `{$template->name}`.";
  }

  public static function inputType(Template $template)
  {
    return new InputObjectType([
      'name' => Utils::normalizeTypeName("{$template->name}UpdateInput"),
      'description' => "UpdateInputType for pages with template {$template->name}.",
      'fields' => self::getInputFields($template),
    ]);
  }

  public static function getInputFields(Template $template)
  {
    $fields = [];

    // parent
    $fields[] = [
      'name' => 'parent',
      'type' => Type::string(),
      'description' => 'Id or the path of the parent page.',
    ];

    // name
    $fields[] = [
      'name' => 'name',
      'type' => Type::string(),
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
    $id = (integer) $args['id'];
    $p = $pages->get($id);
    $p->of(false);

    /*********************************************\
     *                                           *
     * Don't ever take sides against the family! *
     *                                           *
    \*********************************************/
    $parent = null;
    if (isset($values['parent'])) {

      // find the parent
      $parentSelector = $values['parent'];
      $parent = $pages->find($sanitizer->selectorValue($parentSelector))->first();

      // if no parent then no good. No child should born without a parent!
      if (!$parent || $parent instanceof NullPage) {
        throw new ValidationError("Could not find the `parent` page with `$parentSelector`.");
      }

      // make sure user is allowed to add children to this parent
      $legalAddTemplates = Utils::moduleConfig()->legalAddTemplates;
      if (!$legalAddTemplates->has($parent->template)) {
        throw new ValidationError("You are not allowed to add children to the parent: '$parentSelector'.");
      }

      // make sure parent is allowed as a parent for this page
      $parentTemplates = $this->template->parentTemplates;
      if (count($parentTemplates) && !in_array($parent->template->id, $parentTemplates)) {
        throw new ValidationError("`parent` is not allowed as a parent.");
      }

      // make sure parent is allowed to have children
      if ($parent->template->noChildren === 1) {
        throw new ValidationError("`parent` is not allowed to have children.");
      }

      // make sure the page is allowed as a child for parent
      $childTemplates = $parent->template->childTemplates;
      if (count($childTemplates) && !in_array($this->template->id, $childTemplates)) {
        throw new ValidationError("not allowed to be a child for `parent`.");
      }

      $p->parent = $parent;
    }

    if (isset($values['name'])) {
      
      // check if the name is valid
      $name = $sanitizer->pageName($values['name']);
      if (!$name) {
        throw new ValidationError('value for `name` field is invalid,');
      }
      
      // find out if the name is taken
      if (!isset($values['parent'])) {
        $parent = $p->parent;
      }
      $taken = $pages->find("parent=$parent, name=$name")->count();
      if ($taken) {
        throw new ValidationError('`name` is already taken.');
      }

      $p->name = $name;
    }

    // unset the parent and name as we set them above
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
    throw new ResolveError("Could not update page `$name` with template `{$this->template->name}`");
  }
}

