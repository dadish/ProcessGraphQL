<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;

class Checkbox
{ 
  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = new CustomScalarType([
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

    return self::$type;
  }
}
