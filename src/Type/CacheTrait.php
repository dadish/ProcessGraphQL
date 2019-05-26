<?php namespace ProcessWire\GraphQL\Type;

trait CacheTrait
{
  private static $type;
  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = self::buildType();

    return self::$type;
  }

  private static $field = [];
  public static function field($options)
  {
    $name = $options['name'];
    if (isset(self::$field[$name])) {
      return self::$field[$name];
    }

    self::$field[$name] = self::buildField($options);

    return self::$field[$name];
  }
}
