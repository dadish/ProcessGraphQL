<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\TypeCacheTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldCacheTrait;

class Datetime
{ 
  use TypeCacheTrait;
  public static function type()
  {
    return self::cacheType(function () {
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
    });
  }

  use FieldCacheTrait;
  public static function field($options)
  {
    return self::cacheField($options['name'], function () use ($options) {
      return array_merge(
        Resolver::resolveWithDateFormatter($options),
        ['type' => self::type()]
      );
    });
  }
}
