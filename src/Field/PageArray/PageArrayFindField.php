<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;
use ProcessWire\GraphQL\Type\Object\PageArrayType;

class PageArrayFindField extends WireArrayFindField {

  public function getType()
  {
    return new PageArrayType();
  }

  public function getDescription()
  {
    return 'Return all pages that match the given selector, or a blank PageArray if none found.';
  }

}