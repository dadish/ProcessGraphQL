<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;

class PageNameField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'name';
  }

  public function getDescription()
  {
    return 'The name assigned to the page, as it appears in the URL.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->name;
  }

}