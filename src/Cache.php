<?php

namespace ProcessWire\GraphQL;

class Cache
{
  private static function &cache(string $methodName, $store, string $key, $build = null)
  {
    if (isset($store[$key])) {
      return $store[$key];
    }

    if (is_null($build) || !is_callable($build)) {
      throw \Exception("The second argument for Cache::$methodName() should be a callable.");
    }

    $store[$key] = Utils::placeholder();
    $store[$key] = $build();
    return $store[$key];
  }

  /**
   * Type caching
   */
  private static $typeStore = [];

  public static function &type(string $name, $build = null)
  {
    $type =& self::cache('type', self::$typeStore, $name, $build);
    return $type;
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
    $field =& self::cache('field', self::$fieldStore, $name, $build);
    return $field;
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
