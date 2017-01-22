<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\NonNullType;

class PageCreatedField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new IntType());
  }

  public function getName()
  {
    return 'created';
  }

  public function getDescription()
  {
    return "Unix timestamp of when the page was created.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->created;
  }

}