<?php namespace ProcessWire\GraphQL\Type\Traits;

use GraphQL\Type\Definition\Type;

trait InputTypeTrait
{
  public static function inputType($field)
  {
    return self::cache("input-field-{$field->name}", function () use ($field) {
      if ($field->required) {
        return Type::nonNull(self::type());
      }

      return self::type();
    });
  }
}
