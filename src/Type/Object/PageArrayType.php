<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\WireArrayType;
use ProcessWire\PageArray;

class PageArrayType extends WireArrayType {

  public function getName()
  {
    return 'PageArray';
  }

  public function getDescription()
  {
    return 'A WireArray that stores PageTypes';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    if ($value instanceof PageArray) return $value;
    return  \Processwire\wire('pages');
  }

}