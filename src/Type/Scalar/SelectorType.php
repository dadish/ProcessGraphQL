<?php

namespace ProcessWire\GraphQL\Type\Scalar;

use Youshido\GraphQL\Type\Scalar\StringType;

class SelectorType extends StringType {

  const ARGUMENT_NAME = 's';

  public function getName()
  {
    return 'Selector';
  }

  public function getDescription()
  {
    return 'A ProcessWire selector.';
  }
  
}