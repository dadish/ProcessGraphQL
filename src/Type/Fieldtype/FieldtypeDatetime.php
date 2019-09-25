<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;

class FieldtypeDatetime
{ 
  use InputFieldTrait;

  public static $name = 'DateTime';

  public static $description = 'A string that represends a date and optionally time.';

  public static function type()
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

  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return Resolver::resolveWithDateFormatter([
        'name' => $field->name,
        'description' => $desc,
        'type' => $field->required ? Type::nonNull(self::type($field)) : self::type($field),
      ]);
    });
  }

  public static function setValue(Page $page, $field, $value)
  {
  	$fieldName = $field->name;
  	$page->$fieldName = $value->format('Y-m-d H:i:s');
  }
}
