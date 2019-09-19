<?php

namespace ProcessWire\GraphQL;

class Cache
{
  private static $store = [];

  public static function &type(string $name, $build = null)
  {
    if (isset(self::$store[$name])) {
      return self::$store[$name];
    }

    if (is_null($build) || !is_callable($build)) {
      throw \Exception('The second argument for Cache::type() should be a callable.');
    }

    self::$store[$name] = Utils::placeholder();
    self::$store[$name] = $build();
    return self::$store[$name];
  }
}
