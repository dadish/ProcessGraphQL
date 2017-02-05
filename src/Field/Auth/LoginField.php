<?php

namespace ProcessWire\GraphQL\Field\Auth;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Execution\ResolveInfo;

class LoginField extends AbstractField {

  public function getType()
  {
    return new StringType();
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
    $config->addArgument('username', new NonNullType(new StringType()));
    $config->addArgument('password', new NonNullType(new StringType()));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $session = \ProcessWire\wire('session');
    $username = $args['username'];
    $password = $args['password'];
    $user = $session->login($username, $password);
    if (is_null($user)) {
      return 'Failed to login';
    } else {
      return 'Login successful';
    }
  }

}