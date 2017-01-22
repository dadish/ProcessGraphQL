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
    return 'The id of the parent page.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->parentID;
  }

}