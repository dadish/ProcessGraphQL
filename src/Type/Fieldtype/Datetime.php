<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;

class Datetime
{ 
  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = new CustomScalarType([
      'name' => 'Datetime',
      'description' => 'A date and optionally time',
      'serialize' => function ($value) {
        return (string) $value;
      },
      'parseValue' => function ($value) {
        return (string) $value;
      },
      'parseLiteral' => function ($valueNode) {
        return (string) $valueNode->value;
      },
    ]);

    return self::$type;
  }
}
