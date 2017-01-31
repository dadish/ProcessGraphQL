<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PageParentsField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'parents';
  }

  public function getDescription()
  {
    return "Return this page's parent pages as PageArray. Optionally filtered by a selector.";
  }

}