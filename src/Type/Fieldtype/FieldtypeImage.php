<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\ImageType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use GraphQL\Type\Definition\Type;

class FieldtypeImage
{
  use CacheTrait;
  use FieldTrait;
  public static function type()
  {
    return self::cache('dafault', function () {
      return Type::listOf(ImageType::type());
    });
  }
}
