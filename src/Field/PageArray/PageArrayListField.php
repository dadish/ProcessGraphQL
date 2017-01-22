<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Field\WireArray\WireArrayListField;
use ProcessWire\GraphQL\Type\Object\PageType;

use \ChromePhp;

class PageArrayListField extends WireArrayListField {

  public function getType()
  {
    return new ListType(new PageType());
  }

}