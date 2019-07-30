<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputTypeTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypeFile
{
  use CacheTrait;
  use FieldTrait;
  use InputTypeTrait;
  use SetValueTrait;
  public static function type()
  {
    return self::cache('dafault', function () {
      return Type::listOf(FileType::type());
    });
    
  }
}
