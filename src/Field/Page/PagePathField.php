<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;

class PagePathField extends AbstractField {

  public function getType()
  {
    return new StringType();
  }

  public function getName()
  {
    return 'path';
  }

  public function getDescription()
  {
    return "The page's URL path from the homepage (i.e. /about/staff/ryan/)";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->path;
  }

}