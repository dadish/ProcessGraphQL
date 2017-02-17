<?php

namespace ProcessWire\GraphQL\Field\PageArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Field\Traits\RequiredSelectorTrait;

class PageArrayFindField extends AbstractField {

  use RequiredSelectorTrait;

  public function getName()
  {
    return 'find';
  }

  public function getType()
  {
    return new NonNullType(new PageArrayType());
  }

  public function getDescription()
  {
    return 'Return all pages that match the given selector, or a blank PageArray if none found.';
  }

}