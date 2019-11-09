<?php namespace ProcessWire\GraphQL\InputType;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template;
use ProcessWire\Field;
use ProcessWire\Page;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Error\ValidationError;
use ProcessWire\GraphQL\Permissions;
use ProcessWire\GraphQL\Utils;

class PageCreateInputType
{
  public static function getName(Template $template)
  {
    return Utils::normalizeTypeName("{$template->name}CreateInput");
  }
  
  public static function &type(Template $template)
  {  
    $type =& Cache::type(self::getName($template), function () use ($template) {
      return new InputObjectType([
        'name' => self::getName($template),
        'description' => "CreateInputType for pages with template `{$template->name}`.",
        'fields' => self::getFields($template),
      ]);
    });
   
    return $type;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // add built in fields
    $fields = array_merge($fields, self::getBuiltInFields());

    // add template fields
    $fields = array_merge($fields, self::getTemplateFields($template));
    
    // mark required fields as nonNull
    $fields = self::markRequiredTemplateFields($fields, $template);

    return $fields;
  }

  public static function getTemplateFields(Template $template)
  {
    $fields = [];

    // the list of input fields we do not 
    // support for now
    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = implode('|', Utils::module()->legalFields);
    foreach ($template->fields->find("name=$legalFieldsName") as $field) {

      // get the field's GraphQL input class
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) {
        continue;
      }

      // skip the fields that user has no edit permission to
      if (!Permissions::canEditField($field, $template)) {
        continue;
      }

      $f = Utils::pwFieldToGraphqlClass($field);

      // ignore the field if it has no corresponding Graphql class
      if (is_null($f)) {
        continue;
      }

      $inputField = $f::inputField($field);
      $fields[$inputField['name']] = $inputField;
    }

    return $fields; 
  }

  public static function getBuiltInFields()
  {
    $fields = [];

    // parent
    $fields['parent'] = [
      'name' => 'parent',
      'type' => Type::string(),
      'description' => 'Id or the path of the parent page.',
    ];

    // name
    $fields['name'] = [
      'name' => 'name',
      'type' => Type::string(),
      'description' => 'ProcessWire page name.',
    ];

    return $fields;
  }

  public static function markRequiredTemplateFields(array $fields, Template $template)
  {
    // mark parent and name as required if they are set
    if (isset($fields['parent'])) {
      $fields['parent']['type'] = Type::nonNull($fields['parent']['type']);
    }
    if (isset($fields['name'])) {
      $fields['name']['type'] = Type::nonNull($fields['name']['type']);
    }

    // mark required fields as required
    foreach ($template->fields as $field) {
      if (!isset($fields[$field->name])) {
        continue;
      }
      if (!$field->required) {
        continue;
      }
      $fields[$field->name]['type'] = Type::nonNull($fields[$field->name]['type']);
    }

    return $fields;
  }

  public static function setValues(Page $page, array $values)
  {
    // update the values from client
    foreach ($values as $fieldName => $value) {
      $field = Utils::fields()->get($fieldName);

      // check if user has permission to edit this field
      if (!Permissions::canEditField($field, $page->template)) {
        throw new ValidationError("You are not allowed to update field '{$field->name}'");
      }
      
      // skip if field cannot be found
      if (!$field instanceof Field) {
        continue;
      }

      // skip if GraphQL class of a field is not found
      $f = Utils::pwFieldToGraphqlClass($field);
      if (is_null($f)) {
        continue;
      }
      
      // set value of a field
      $f::setValue($page, $field, $value);
    }
  }
}
