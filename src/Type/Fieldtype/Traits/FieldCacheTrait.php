<?php namespace ProcessWire\GraphQL\Type\Fieldtype\Traits;

trait FieldCacheTrait
{
  private static $field = [];

  private static function cacheField(string $name, $createField)
  {
    if (isset(self::$field[$name])) {
      return self::$field[$name];
    }

    if (is_callable($createField)) {
      self::$field[$name] = $createField();
    } else {
      self::$field[$name] = $createField;
    }

    return self::$field[$name];
  }
}
