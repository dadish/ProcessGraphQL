<?php

namespace ProcessWire\GraphQL\Field\User;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\UserType;

class UserField extends AbstractField {
  
  public function getType()
  {
    return new NonNullType(new UserType());
  }

  public function getName()
  {
    return 'user';
  }

  public function getDescription()
  {
    return 'The current user of the app.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return \ProcessWire\wire('user');
  }

}