<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;

class PageNameField extends AbstractField {

  public function getType()
  {
    return new StringType();
  }

  public function getName()
  {
    return 'name';
  }

  public function getDescription()
  {
    return 'The name of the page.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->name;
  }

}