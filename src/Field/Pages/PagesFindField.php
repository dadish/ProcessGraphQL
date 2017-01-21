<?php

namespace ProcessWire\GraphQL\Field\Pages;

use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;
use ProcessWire\GraphQL\Type\Object\PageArrayType;

class PagesFindField extends WireArrayFindField {

  public function gettype()
  {
    return new PageArrayType();
  }

}