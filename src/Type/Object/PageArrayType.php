<?php

namespace ProcessWire\GraphQL\Type\Object;

use ProcessWire\PageArray;
use ProcessWire\GraphQL\Type\Object\WireArrayType;
use ProcessWire\GraphQL\Field\PageArray\PageArrayListField;
use ProcessWire\GraphQL\Field\PageArray\PageArrayFindField;

class PageArrayType extends WireArrayType {

  public function getName()
  {
    return 'PageArray';
  }

  public function getDescription()
  {
    return 'A WireArray that stores PageTypes';
  }

  public function build($config)
  {
    parent::build($config);
    $config->addField(new PageArrayListField());
    $config->addField(new PageArrayFindField());
  }

}