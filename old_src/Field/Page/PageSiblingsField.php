<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PageSiblingsField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'siblings';
  }

  public function getDescription()
  {
    return "All the sibling pages of this page. Optionally filter them with selector. Returns a PageArray.";
  }

}