<?php namespace ProcessWire\GraphQL\Type\Fieldtype\Traits;

trait TypeCacheTrait
{
  private static $type;

  private static function cacheType($createType)
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = $createType();

    return self::$type;
  }
}
