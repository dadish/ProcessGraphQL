<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Type\InterfaceType\PageFileInterfaceType;

class PageImageType extends AbstractObjectType {

  public function getName()
  {
    return 'PageImage';
  }

  public function getDescription()
  {
    return 'ProcessWire PageImage.';
  }

  public function build($config)
  {
      
    $config->applyInterface(new PageFileInterfaceType());

    $config->addfield('width', [
      'type' => new IntType(),
      'description' => 'The width of the image.',
      'resolve' => function ($value) {
        return (integer) $value->width;
      }
    ]);

    $config->addfield('height', [
      'type' => new IntType(),
      'description' => 'The height of the image.',
      'resolve' => function ($value) {
        return (integer) $value->height;
      }
    ]);
  }

  public function getInterfaces()
  {
    return [ new PageFileInterfaceType() ];
  }

}