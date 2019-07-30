<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;

class FieldtypeInteger
{ 
  use CacheTrait;
  use FieldTrait;
  public static function type()
  {
    return Type::int();
  }
}
