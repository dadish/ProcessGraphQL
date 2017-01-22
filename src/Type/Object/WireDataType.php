<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;

class WireDataType extends AbstractObjectType {

  public function getName()
  {
    return 'WireData';
  }

  public function build($config)
  {
    
  }

  public function getDescription()
  {
    return 'ProcessWire WireData type.';
  }

}