<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Field\Traits\DatetimeResolverTrait;
use ProcessWire\Page;

class FieldtypeDatetime extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new StringType();
  }

  use DatetimeResolverTrait;

  public function setValue(Page $page, $value)
  {
  	$fieldName = $this->field->name;
  	$page->$fieldName = $value->format('Y-m-d H:i:s');
  }

}
