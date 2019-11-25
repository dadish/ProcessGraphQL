<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\Page;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeInteger
{ 
  use InputFieldTrait;
  use SetValueTrait;
  public static function type()
  {
    return Type::int();
  }

  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
        'resolve' => function (Page $page) use ($field) {
          $fieldName = $field->name;
          $value = $page->$fieldName;
          if (empty($value) && !is_int($value)) {
            return null;
          }
          return $value;
        }
      ];
    });
  }
}
