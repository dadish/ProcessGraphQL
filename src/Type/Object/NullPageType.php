<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\ListType\ListType;

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
    // Empty string properties
    $fields = ['path', 'url', ];
    foreach ($fields as $field) {
      $config->addField($field, [
        'type' => new StringType(),
        'description' => 'Returns empty string.',
        'resolve' => function () {
          return '';
        }
      ]);
    }

    // null properties
    $fields = ['parent', 'prev', 'next', 'rootParent'];
    foreach ($fields as $field) {
      $config->addField($field, [
        'type' => new StringType(),
        'description' => 'Returns `null`.',
        'resolve' => function () {
          return null;
        }
      ]);
    }

    // empty array properties
    $fields = ['parents', 'children', 'siblings'];
    foreach ($fields as $field) {
      $config->addField($field, [
        'type' => new ListType(new StringType()),
        'description' => 'Returns empty list.',
        'resolve' => function () {
          return [];
        }
      ]);
    }

  }

}