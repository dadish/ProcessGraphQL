<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;

class PageHttpUrlField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'httpUrl';
  }

  public function getDescription()
  {
    return 'Same as `url`, except includes protocol (http or https) and hostname.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->httpUrl;
  }

}