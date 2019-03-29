<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeTextarea extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new StringType();
  }

}