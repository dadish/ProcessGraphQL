<?php

namespace ProcessWire\GraphQL\Field\Auth;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\AuthResponseType;
use ProcessWire\WireData;

class LoginField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new AuthResponseType());
  }

  public function getName()
  {
    return 'login';
  }

  public function getDescription()
  {
    return 'Allows you to authenticate into the app.';
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument('name', new NonNullType(new StringType()));
    $config->addArgument('pass', new NonNullType(new StringType()));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $session = \ProcessWire\wire('session');
    $username = $args['name'];
    $password = $args['pass'];
    $user = $session->login($username, $password);
    $response = new WireData();
    if (is_null($user)) {
      $response->statusCode = 401;
      $response->message = 'Wrong username and/or password.';
    } else {
      $response->statusCode = 200;
      $response->message = 'Successful login!';
    }
    return $response;
  }

}