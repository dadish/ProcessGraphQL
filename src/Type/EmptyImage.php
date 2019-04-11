<?php

namespace ProcessWire\GraphQL\Type;

use ProcessWire\WireData;
use ProcessWire\WireArray;

class EmptyImage extends WireData {

  public function getVariations()
  {
    return new WireArray();
  }

}
