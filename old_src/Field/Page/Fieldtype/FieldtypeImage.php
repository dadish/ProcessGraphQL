<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\FieldtypeFile;
use ProcessWire\GraphQL\Type\Object\PageImageType;

class FieldtypeImage extends FieldtypeFile {

  public function getDefaultType()
  {
    return new ListType(new PageImageType());
  }

}