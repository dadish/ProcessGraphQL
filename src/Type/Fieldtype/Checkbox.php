<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldCacheTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\TypeCacheTrait;

class Checkbox
{ 
  use TypeCacheTrait;
  public static function type()
  {
    $type = new CustomScalarType([
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
    return self::cacheType($type);
  }

  use FieldCacheTrait;
  public static function field($options)
  {
    $field = array_merge($options, [
      'type' => self::type(),
    ]);
    return self::cacheField($options['name'], $field);
  }
}
