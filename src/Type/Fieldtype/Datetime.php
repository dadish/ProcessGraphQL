<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\CacheTrait;

class Datetime
{ 
  use CacheTrait;
  public static function buildType()
  {
    return new CustomScalarType([
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
  }

  public static function buildField($options)
  {
    return array_merge(
      Resolver::resolveWithDateFormatter($options),
      ['type' => self::type()]
    );
  }
}
