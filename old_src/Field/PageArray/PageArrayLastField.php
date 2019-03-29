<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;

class PageArrayLastField extends AbstractField {

  public function getName()
  {
    return 'last';
  }

  public function getType()
  {
    return new PageInterfaceType();
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->last();
  }

}