<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PageNextField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'next';
  }

  public function getDescription()
  {
    return "This page's next sibling page, or null if it is the last sibling.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $next = $value->next;
    if ($next instanceof NullPage) return null;
    return $next;
  }

}