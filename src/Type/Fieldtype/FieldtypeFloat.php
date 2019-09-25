<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypeFloat
{ 
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  public static function type()
  {
    return Type::float();
  }
}
