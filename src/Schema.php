<?php

namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Type\Scalar\StringType;

class Schema extends AbstractSchema {
  public function build(SchemaConfig $config)
  {
    $config->getQuery()->addFields([
      'hello' => [
        'type' => new StringType(),
        'resolve' => function () {
          return 'world';
        }
      ]
    ]);
  }
}