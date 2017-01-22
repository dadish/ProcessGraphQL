<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;

class PageRootParentField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'rootParent';
  }

  public function getDescription()
  {
    return 'The parent page closest to the homepage (typically used for identifying a section)';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->rootParent;
  }

}