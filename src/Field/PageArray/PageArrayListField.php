<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;

class PageArrayListField extends AbstractField {

  public function getName()
  {
    return 'list';
  }

  public function getType()
  {
    return new ListType(new PageInterfaceType());
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value;
  }

}