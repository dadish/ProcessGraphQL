<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\TypeCacheTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldCacheTrait;

class Email
{ 
  use TypeCacheTrait;
  public static function type()
  {
    $type = new CustomScalarType([
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
