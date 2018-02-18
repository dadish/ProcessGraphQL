<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class SelectableOptionType extends AbstractObjectType {

  public function getName()
  {
    return 'SelectableOption';
  }

  public function getDescription()
  {
    return 'Object type that represents the ProcessWire Selectable Option.';
  }

  public function build($config)
  {
    $config->addField('title', [
      'type' => new NonNullType(new StringType()),
      'description' => 'The title of the selected option.',
      'resolve' => function ($value) {
        return (string) $value->title;
      }
    ]);

    $config->addField('value', [
      'type' => new StringType(),
      'description' => 'The value of the selected option.',
      'resolve' => function ($value) {
        return (string) $value->value;
      }
    ]);

    $config->addField('id', [
      'type' => new NonNullType(new IntType()),
      'description' => 'The id of the selected option.',
      'resolve' => function ($value) {
        return (integer) $value->id;
      }
    ]);
  }

}