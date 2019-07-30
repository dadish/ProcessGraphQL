<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputTypeTrait;

class FieldtypeInteger
{ 
  use CacheTrait;
  use FieldTrait;
  use InputTypeTrait;
  public static function type()
  {
    return Type::int();
  }
}
