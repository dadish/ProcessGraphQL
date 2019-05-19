<?php namespace ProcessWire\GraphQL\Type\Fieldtype\Traits;

trait FieldCacheTrait
{
  private static $field = [];

  private static function cacheField(string $name, $createField)
  {
    if (isset(self::$field[$name])) {
      return self::$field[$name];
    }

    self::$field[$name] = $createField();

    return self::$field[$name];
  }
}
