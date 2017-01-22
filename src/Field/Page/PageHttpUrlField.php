<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;

class PageHttpUrlField extends AbstractField {

  public function getType()
  {
    return new StringType();
  }

  public function getName()
  {
    return 'httpUrl';
  }

  public function getDescription()
  {
    return 'The http url of the page. Includes the protocol & hostname.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->httpUrl;
  }

}