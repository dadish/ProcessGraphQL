<?php

namespace ProcessWire\GraphQL\Field\WireArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class WireArrayCountField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new IntType();
  }

  public function getName()
  {
    return 'count';
  }

  public function getDescription()
  {
    return 'Count and return how many items will match the given selector.';
  }

}