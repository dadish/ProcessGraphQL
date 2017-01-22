<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;
use ProcessWire\GraphQL\Type\Object\PageArrayType;

class PageArrayFindField extends WireArrayFindField {

  public function getType()
  {
    return new PageArrayType();
  }

}