<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;

class FieldtypeCheckbox
{ 
  use CacheTrait;
  use FieldTrait;
  public static function type()
  {
    return self::cache('default', function () {
      return new CustomScalarType([
        'name' => 'Checkbox',
        'description' => 'An ON/OFF toggle via a single checkbox.',
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
