<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Fieldtype\CacheTrait;

class Checkbox
{ 
  use CacheTrait;
  public static function buildType()
  {
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
  }

  public static function buildField($options)
  {
    return array_merge($options, [
      'type' => self::type(),
    ]);
  }
}
