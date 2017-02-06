<?php

namespace ProcessWire\GraphQL\Field\Debug;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Execution\ResolveInfo;

class DBQueryCountField extends AbstractField {

  public function getName()
  {
    return 'dbQueryCount';
  }

  public function getDescription()
  {
    return 'The total number of queries to database made to fulfill this request.';
  }

  public function getType()
  {
    return new NonNullType(new IntType());
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return count(\ProcessWire\Database::getQueryLog());
  }

}