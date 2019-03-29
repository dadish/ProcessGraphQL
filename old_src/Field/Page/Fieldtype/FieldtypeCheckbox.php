<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\BooleanType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use Youshido\GraphQL\Execution\ResolveInfo;

class FieldtypeCheckbox extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new BooleanType();
  }
  
  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    return (boolean) $value->$fieldName;
  }

}
