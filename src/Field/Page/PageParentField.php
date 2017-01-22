<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;

class PageParentField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'parent';
  }

  public function getDescription()
  {
    return 'The parent page of the page.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->parent;
  }

}