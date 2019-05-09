<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use ProcessWire\Template;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageType;

class TemplatedPageType
{
  private static $types = [];

  public static function type(Template $template)
  {
		if (isset(self::$types[$template->name])) {
			return self::$types[$template->name];
		}

    $selfType = null;
    $selfType = new ObjectType([
      'name' => self::getName($template),
      'description' => self::getDescription($template),
      'fields' => function () use (&$selfType, $template) {
        return self::getFields($selfType, $template);
      },
    ]);
    
    self::$types[$template->name] = $selfType;
    return $selfType;
  }

  public Static function normalizeName($name)
  {
    return str_replace('-', '_', $name);
  }

  public static function getName(Template $template)
  {
    return ucfirst(self::normalizeName($template->name)) . 'Page';
  }

  public static function getDescription(Template $template)
  {
    $desc = $template->description;
    if ($desc) return $desc;
    return "PageType with template `" . $template->name . "`.";
  }

  public static function getFields(&$selfType, Template $template)
  {
    $fields = [];

    // add the template fields
    $legalFields = Utils::moduleConfig()->legalFields;
    foreach ($template->fields as $field) {
      // skip illigal fields
      if (!$legalFields->has($field)) {
        continue;
      }

      // check if user has permission to view this field
      if (!Utils::hasFieldPermission('view', $field, $template)) {
        continue;
      }

      $fieldClass = Utils::pwFieldToGraphqlClass($field);
      if (is_null($fieldClass)) {
        continue;
      }

      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      $fields[] = [
        'name' => $field->name,
        'description' => $desc,
        'type' => $fieldClass::type(),
      ];
    }

    // add all the built in page fields
    foreach (PageType::type()->getFields() as $field) {
      $fields[] = $field;
    }

    return $fields;
  }
}
