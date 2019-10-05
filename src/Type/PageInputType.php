<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Utils;

class PageInputType
{
  public static function getName(Template $template)
  {
    $postfix = $template->graphqlCreateField ? 'CreateInput' : 'UpdateInput';
    return Utils::normalizeTypeName("{$template->name}{$postfix}");
  }

  public static function &type(Template $template)
  {
   
    $type =& Cache::type(self::getName($template), function () use ($template) {
      return new InputObjectType([
        'name' => self::getName($template),
        'fields' => self::getFields($template),
      ]);
    });
   
    return $type;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // if it is not a repeater page then user can update some builtIn fields too.
    if (!Utils::isRepeaterTemplate($template)) {
      $fields = array_merge($fields, self::getBuiltInFields($template));
    }

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

      // ignore the field if it has no corresponding Graphql class
      if (is_null($f)) {
        continue;
      }

      $fieldSettings = $f::inputField($field);

      // if it is a create input type then mark required fields as nonNull
      if ($field->required && $template->graphqlCreateField) {
        $fieldSettings['type'] = Type::nonNull($fieldSettings['type']);
      }

      $fields[] = $fieldSettings;
    }

    return $fields;
  }

  public static function getBuiltInFields(Template $template)
  {
    $fields = [];

    // parent
    $parent = [
      'name' => 'parent',
      'type' => Type::string(),
      'description' => 'Id or the path of the parent page.',
    ];
    if ($template->graphqlCreateField) {
      $parent['type'] = Type::nonNull($parent['type']);
    }
    $fields[] = $parent;

    // name
    $name = [
      'name' => 'name',
      'type' => Type::string(),
      'description' => 'ProcessWire page name.',
    ];
    if ($template->graphqlCreateField) {
      $name['type'] = Type::nonNull($name['type']);
    }
    $fields[] = $name;

    return $fields;
  }
}
