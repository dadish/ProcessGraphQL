<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;

class PageType extends AbstractObjectType {
  
  public function getName()
  {
    return 'Page';
  }

  public function getDescription()
  {
    return 'A ProcessWire Page object.';
  }

  public function build($config)
  {
    $config->applyInterface(new PageInterfaceType());
  }

  public function getInterfaces()
  {
      return [ new PageInterfaceType() ];
  }

}