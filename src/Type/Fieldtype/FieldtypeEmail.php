<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputTypeTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypeEmail
{ 
  use CacheTrait;
  use FieldTrait;
  use InputTypeTrait;
  use SetValueTrait;
  public static function type()
  {
    return self::cache('default', function () {
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
    });
  }
}
