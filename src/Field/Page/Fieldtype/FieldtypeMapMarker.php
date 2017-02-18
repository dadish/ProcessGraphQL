<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\MapMarkerType;
use ProcessWire\GraphQL\Type\Input\Inputfield\InputfieldMapMarker;

class FieldtypeMapMarker extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new MapMarkerType();
  }

  public function getInputfieldType($type = null)
  {
  	return new InputfieldMapMarker();
  }

}