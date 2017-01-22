<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PagePrevField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'prev';
  }

  public function getDescription()
  {
    return "This page's previous sibling page, or null if it is the first sibling.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $prev = $value->prev;
    if ($prev instanceof NullPage) return null;
    return $prev;
  }

}