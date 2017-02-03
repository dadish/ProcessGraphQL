<?php

namespace ProcessWire\GraphQL\Type\Object;

use ProcessWire\GraphQL\Type\Object\WireArrayType;
use ProcessWire\GraphQL\Field\PageArray\PageArrayListField;
use ProcessWire\GraphQL\Field\PageArray\PageArrayFindField;
use ProcessWire\GraphQL\Type\InterfaceType\PaginatedArrayType as PaginatedArrayInterfaceType;

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
    $config->applyInterface(new PaginatedArrayInterfaceType());
    $config->addField(new PageArrayListField());
    $config->addField(new PageArrayFindField());
  }

}