<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;

class PageChildrenField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'children';
  }

  public function getDescription()
  {
    return "All the children (subpages) of this page.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->children;
  }

}