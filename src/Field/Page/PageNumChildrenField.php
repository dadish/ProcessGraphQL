<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\NonNullType;

class PageNumChildrenField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new IntType());
  }

  public function getName()
  {
    return 'numChildren';
  }

  public function getDescription()
  {
    return "The number of children (subpages) this page has.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->numChildren;
  }

}