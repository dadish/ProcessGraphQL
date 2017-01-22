<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IdType;

class PageParentIdField extends AbstractField {

  public function getType()
  {
    return new IdType();
  }

  public function getName()
  {
    return 'parentID';
  }

  public function getDescription()
  {
    return 'The numbered ID of the parent page or 0 if none.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->parentID;
  }

}