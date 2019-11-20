<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\FileType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;

class FieldtypeFile
{
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  public static function type($field)
  {
    return Type::listOf(FileType::type($field));
  }
}
