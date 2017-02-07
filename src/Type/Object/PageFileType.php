<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use ProcessWire\GraphQL\Type\InterfaceType\PageFileInterfaceType;

class PageFileType extends AbstractObjectType {

  public function getName()
  {
    return 'PageFile';
  }

  public function getDescription()
  {
    return 'ProcessWire PageFile.';
  }

  public function build($config)
  {
    $config->applyInterface(new PageFileInterfaceType());
  }

  public function getInterfaces()
  {
      return [ new PageFileInterfaceType() ];
  }

}