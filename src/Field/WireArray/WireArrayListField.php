<?php

namespace ProcessWire\GraphQL\Field\WireArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\WireDataType;

class WireArrayListField extends AbstractField {

  public function getType()
  {
    return new ListType(new WireDataType());
  }

  public function getName()
  {
    return 'list';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value;
  }

}