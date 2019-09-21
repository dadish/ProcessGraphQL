<?php

namespace ProcessWire\GraphQL;

class Cache
{
  /**
   * Type caching
   */
  private static $typeStore = [];

  public static function &type(string $name, $build = null)
  {
    if (isset(self::$typeStore[$name])) {
      return self::$typeStore[$name];
    }

    if (is_null($build) || !is_callable($build)) {
      throw \Exception("The second argument for Cache::type() should be a callable.");
    }

    self::$typeStore[$name] = Utils::placeholder();
    self::$typeStore[$name] = $build();
    return self::$typeStore[$name];
  }

  public static function clearType()
  {
    self::$typeStore = [];
  }


  /**
   * Field caching
   */
  private static $fieldStore = [];

  public static function &field(string $name, $build = null)
  {
    if (isset(self::$fieldStore[$name])) {
      return self::$fieldStore[$name];
    }

    if (is_null($build) || !is_callable($build)) {
      throw \Exception("The second argument for Cache::field() should be a callable.");
    }

    self::$fieldStore[$name] = Utils::placeholder();
    self::$fieldStore[$name] = $build();
    return self::$fieldStore[$name];
  }

  public static function clearField()
  {
    self::$fieldStore = [];
  }


  public static function clear()
  {
    self::$typeStore = [];
    self::$fieldStore = [];
  }
}
