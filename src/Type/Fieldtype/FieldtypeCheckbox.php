<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\CacheTrait;

class FieldtypeCheckbox
{ 
  use CacheTrait;
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

  public static function field($options)
  {
    return self::cache('field-' . $options['name'], function () use ($options) {
      return array_merge($options, [
        'type' => self::type(),
      ]);
    });
  }
}
