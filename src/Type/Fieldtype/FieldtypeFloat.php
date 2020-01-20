<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\Field;
use ProcessWire\Page;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeFloat
{ 
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  public static function type()
  {
    return Type::float();
  }

  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      return [
        'name' => $field->name,
        'type' => self::type($field),
        'resolve' => function (Page $page) use ($field) {
          $fieldName = $field->name;
          $value = floatval($page->$fieldName);
          return $value;
        }
      ];
    });
  }
}
