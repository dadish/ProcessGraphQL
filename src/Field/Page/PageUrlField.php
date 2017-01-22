<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;

class PageUrlField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'url';
  }

  public function getDescription()
  {
    return "The page's URL path from the server's document root (may be the same as the `path`)";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->url;
  }

}