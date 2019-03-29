<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\NonNullType;

class UserType extends AbstractObjectType {

  public function getName()
  {
    return 'User';
  }

  public function getDescription()
  {
    return 'Represents ProcessWire User.';
  }

  public function build($config)
  {
    $config->addField('name', [
      'type' => new NonNullType(new StringType()),
      'description' => "The user's login name.",
      'resolve' => function ($value) {
        return (string) $value->name;
      }
    ]);

    $config->addField('email', [
      'type' => new NonNullType(new StringType()),
      'description' => "The user's email address.",
      'resolve' => function ($value) {
        return (string) $value->email;
      }
    ]);

    $config->addField('id', [
      'type' => new NonNullType(new IdType()),
      'description' => "The user's id.",
      'resolve' => function ($value) {
        return (string) $value->id;
      }
    ]);
  }

}