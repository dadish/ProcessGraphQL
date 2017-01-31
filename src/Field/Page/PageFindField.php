<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;
use ProcessWire\GraphQL\Field\Traits\RequiredSelectorTrait;

class PageFindField extends AbstractField {

  use RequiredSelectorTrait;

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'find';
  }

  public function getDescription()
  {
    return "Find pages matching the selector anywhere below this page (children, grandchildren, etc.). Returns a PageArray.";
  }

}