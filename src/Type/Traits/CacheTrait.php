<?php namespace ProcessWire\GraphQL\Type\Traits;

use ProcessWire\GraphQL\Utils;

trait CacheTrait
{
  private static $store = [];
  private static function &cache($key = 'default', $build)
  {
    if (isset(self::$store[$key])) {
      return self::$store[$key];
    }

    self::$store[$key] = Utils::placeholder();
    self::$store[$key] = $build();
    return self::$store[$key];
  }
}
