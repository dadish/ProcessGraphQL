<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\Fieldtype\CacheTrait;

class File
{
  use CacheTrait;
  public static function type()
  {
    return FileType::type();
  }

  public static function buildField($options)
  {
    return array_merge($options, [
      'type' => self::type(),
    ]);
  }
}
