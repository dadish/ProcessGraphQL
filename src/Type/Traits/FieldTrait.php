<?php namespace ProcessWire\GraphQL\Type\Traits;

use ProcessWire\Template;
use GraphQL\Type\Definition\Type;

trait FieldTrait
{
  public static function field($field)
  {
    return self::cache("field-{$field->name}", function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => $field->required ? Type::nonNull(self::type($field)) : self::type($field),
      ];
    });
  }
}
