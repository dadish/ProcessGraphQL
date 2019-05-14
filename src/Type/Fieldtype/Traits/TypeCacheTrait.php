<?php namespace ProcessWire\GraphQL\Type\Fieldtype\Traits;

trait TypeCacheTrait
{
  private static $type;

  private static function cacheType($createType)
  {
    if (self::$type) {
      return self::$type;
    }

    if (is_callable($createType)) {
      self::$type = $createType();
    } else {
      self::$type = $createType;
    }

    return self::$type;
  }
}
