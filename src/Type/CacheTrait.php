<?php namespace ProcessWire\GraphQL\Type;

use ProcessWire\Template;

trait CacheTrait
{
  private static $type;
  public static function type($options = null)
  {
    if ($options instanceof Template) {
      return self::templateType($options);
    }

    if (self::$type) {
      return self::$type;
    }

    self::$type = self::buildType();

    return self::$type;
  }

  private static $types = [];
  public static function templateType(Template $template)
  {
    if (isset(self::$types[$template->name])) {
      return self::$types[$template->name];
    }

    self::$types[$template->name] = self::buildTemplateType($template);
    return self::$types[$template->name];
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

  public static function cacheType($key = 'default', $buildType)
  {
    if (isset(self::$types[$key])) {
      return self::$types[$key];
    }

    self::$types[$key] = $buildType();
    return self::$types[$key];
  }
}
