<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PageChildrenField extends AbstractField {

  use OptionalSelectorTrait;

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
    return "All the children (subpages) of this page, optionally filtered by a selector.";
  }

}