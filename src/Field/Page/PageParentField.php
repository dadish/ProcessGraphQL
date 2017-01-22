<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

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
    return 'The parent Page object or a `null` if there is no parent.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $parent = $value->parent;
    if ($parent instanceof NullPage) return null;
    return $parent;
  }

}