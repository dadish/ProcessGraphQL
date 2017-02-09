<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\BooleanType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeCheckbox extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new BooleanType();
  }

}