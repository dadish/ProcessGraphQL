<?php namespace ProcessWire\GraphQL\Type\Traits;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Cache;

trait FieldTrait
{
  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
      ];
    });
  }
}
