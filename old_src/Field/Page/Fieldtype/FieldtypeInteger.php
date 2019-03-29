<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeInteger extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new IntType();
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    return (integer) $value->$fieldName;
  }

}