<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Execution\ResolveInfo;

class Pages extends AbstractField {

  public function getType()
  {
    return new ListType(new StringType());
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return ['boo', 'bar'];
  }

}