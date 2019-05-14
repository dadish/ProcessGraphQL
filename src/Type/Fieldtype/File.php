<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldCacheTrait;

class File
{
  public static function type()
  {
    return FileType::type();
  }

  use FieldCacheTrait;
  public static function field($options)
  {
    $field = array_merge($options, [
      'type' => self::type(),
    ]);
    return self::cacheField($options['name'], $field);
  }
}
