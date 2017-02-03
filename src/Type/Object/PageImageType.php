<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Type\Object\PageFileType;

class PageImageType extends PageFileType {

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
      
    parent::build($config);

    $config->addfield('width', [
      'type' => new IntType(),
      'resolve' => function ($value) {
        return (integer) $value->width;
      }
    ]);

    $config->addfield('height', [
      'type' => new IntType(),
      'resolve' => function ($value) {
        return (integer) $value->height;
      }
    ]);
  }

}