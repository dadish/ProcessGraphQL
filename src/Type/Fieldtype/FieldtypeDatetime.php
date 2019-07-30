<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\InputTypeTrait;

class FieldtypeDatetime
{ 
  use CacheTrait;
  use InputTypeTrait;
  public static function type()
  {
    return self::cache('default', function () {
      return new CustomScalarType([
        'name' => 'Datetime',
        'description' => 'A string that represends a date and optionally time.',
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

  public static function field($field)
  {
    return self::cache("field-{$field->name}", function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return Resolver::resolveWithDateFormatter([
        'name' => $field->name,
        'description' => $desc,
        'type' => $field->required ? Type::nonNull(self::type()) : self::type(),
      ]);
    });
  }
}
