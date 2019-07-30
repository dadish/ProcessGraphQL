<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;

class FieldtypeFile
{
  use CacheTrait;
  use FieldTrait;
  public static function type()
  {
    return self::cache('dafault', function () {
      return Type::listOf(FileType::type());
    });
    
  }
}
