<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Fieldtype\CacheTrait;

class Email
{ 
  use CacheTrait;
  public static function buildType()
  {
    return new CustomScalarType([
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
  }

  public static function buildField($options)
  {
    return array_merge($options, [
      'type' => self::type(),
    ]);
  }
}
