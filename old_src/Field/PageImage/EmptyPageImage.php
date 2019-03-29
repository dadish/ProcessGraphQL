<?php

namespace ProcessWire\GraphQL\Field\PageImage;

use ProcessWire\WireData;
use ProcessWire\WireArray;

class EmptyPageImage extends WireData {

  public function getVariations()
  {
    return new WireArray();
  }

}
