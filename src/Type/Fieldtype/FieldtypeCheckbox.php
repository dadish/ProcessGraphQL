<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeCheckbox
{ 
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  
  public static $name = 'Checkbox';

  public static $description = 'An ON/OFF toggle via a single checkbox.';

  public static function type()
  {
    return Cache::type(self::$name, function () {
      return new CustomScalarType([
        'name' => self::$name,
        'description' => self::$description,
        'serialize' => function ($value) {
          return (boolean) $value;
        },
        'parseValue' => function ($value) {
          return (boolean) $value;
        },
        'parseLiteral' => function ($valueNode) {
          return (boolean) $valueNode->value;
        },
      ]);
    });
  }
}
