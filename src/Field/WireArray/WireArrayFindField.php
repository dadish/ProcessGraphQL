<?php

namespace ProcessWire\GraphQL\Field\WireArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\WireArrayType;
use ProcessWire\GraphQL\Field\Traits\RequiredSelectorTrait;

class WireArrayFindField extends AbstractField {

  use RequiredSelectorTrait;

  public function getType()
  {
    return new NonNullType(new WireArrayType());
  }

  public function getName()
  {
    return 'find';
  }

  public function getDescription()
  {
    return 'Return all items that match the given selector, or a blank WireArray if none found.';
  }

}