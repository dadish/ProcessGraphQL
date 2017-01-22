<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PageChildField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'child';
  }

  public function getDescription()
  {
    return 'The first child of this page. Returns a Page or null.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $child = $value->child;
    if ($child instanceof NullPage) return null;
    return $child;
  }

}