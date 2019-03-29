<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class AuthResponseType extends AbstractObjectType {

  public function getName()
  {
    return 'AuthResponse';
  }

  public function getDescription()
  {
    return 'Object type that represents the authentication response.';
  }

  public function build($config)
  {
    $config->addField('statusCode', [
      'type' => new NonNullType(new IntType()),
      'description' => 'The authentication status code. E.g. 200 if successful.',
      'resolve' => function ($value) {
        return (integer) $value->statusCode;
      }
    ]);

    $config->addField('message', [
      'type' => new NonNullType(new StringType()),
      'description' => 'Homan readable message of the authentication reponse. E.g. "successful login!"',
      'resolve' => function ($value) {
        return (string) $value->message;
      }
    ]);
  }

}