<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;

class PageImageType extends AbstractObjectType {

  public function getName()
  {
    return 'PageImage';
  }

  public function build($config)
  {
    $config->addFields([
      
      'url' => [
        'type' => new StringType(),
        'resolve' => function ($value) {
          return (string) $value->url;
        }
      ],
      
      'width' => [
        'type' => new IntType(),
        'resolve' => function ($value) {
          return (integer) $value->width;
        }
      ],

      'height' => [
        'type' => new IntType(),
        'resolve' => function ($value) {
          return (integer) $value->height;
        }
      ],

      'basename' => [
        'type' => new StringType(),
        'resolve' => function ($value) {
          return (string) $value->basename;
        }
      ],
    ]);
  }

}