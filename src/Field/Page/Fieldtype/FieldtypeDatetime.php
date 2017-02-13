<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\DateTimeType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeDatetime extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new DatetimeType();
  }

}
