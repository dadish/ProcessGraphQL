<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use ProcessWire\GraphQL\Type\Union\PageUnion;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PageParentField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new PageUnion();
  }

  public function getName()
  {
    return 'parent';
  }

  public function getDescription()
  {
    return 'The parent Page object, or the closest parent matching the given selector. Returns `null` if there is no parent or no match.';
  }

}