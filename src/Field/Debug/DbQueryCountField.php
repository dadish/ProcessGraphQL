<?php

namespace ProcessWire\GraphQL\Field\Debug;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Execution\ResolveInfo;

class DBQueryCountField extends AbstractField {

  public function getName()
  {
    return 'dbQueryCount';
  }

  public function getType()
  {
    return new IntType();
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return count(\ProcessWire\wire('database')->queryLog());
  }

}