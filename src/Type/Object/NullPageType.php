<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;

class NullPageType extends AbstractObjectType {
  
  public function getName()
  {
    return 'NullPage';
  }

  public function getDescription()
  {
    return 'A ProcessWire NullPage object.';
  }

  public function build($config)
  {
    $config->applyInterface(new PageInterfaceType());
  }

  public function getInterfaces()
  {
      return [new PageInterfaceType()];
  }

}