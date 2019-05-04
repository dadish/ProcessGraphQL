<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;

class File
{
  public static function type()
  {
    return FileType::type();
  }
}
