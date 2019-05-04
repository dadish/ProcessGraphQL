<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;

class Email
{ 
  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = new CustomScalarType([
      'name' => 'Email',
      'description' => 'E-Mail address in valid format',
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
