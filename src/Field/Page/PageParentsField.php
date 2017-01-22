<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;

class PageParentsField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'parents';
  }

  public function getDescription()
  {
    return "Return this page's parent pages as PageArray.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->parents;
  }

}