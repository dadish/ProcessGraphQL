<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\CacheTrait;

class File
{
  use CacheTrait;
  public static function type()
  {
    return FileType::type();
  }

  public static function field($options)
  {
    return self::cache('field-' . $options['name'], function () use ($options) {
      return array_merge($options, [
        'type' => self::type(),
      ]);
    });
  }
}
