<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\ImageType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypeImage
{
  use CacheTrait;
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  public static function type($field)
  {
    return self::cache($field->name, function () use ($field) {
      return Type::listOf(ImageType::type($field));
    });
  }
}
