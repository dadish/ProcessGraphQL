<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypePageTitle
{ 
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;

  public static $name = 'PageTitle';

  public static $description = 'Field that stores a page title';

  public static function type($field)
  {
    return Cache::type(self::$name, function () {
      return new CustomScalarType([
        'name' => self::$name,
        'description' => self::$description,
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
