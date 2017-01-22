<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IdType;

class PageIdField extends AbstractField {

  public function getType()
  {
    return new IdType();
  }

  public function getName()
  {
    return 'id';
  }

  public function getDescription()
  {
    return 'The numbered ID of the page.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->id;
  }

}