<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\MapMarkerType;
use ProcessWire\GraphQL\Type\Input\Inputfield\InputfieldMapMarker;
use ProcessWire\Page;

class FieldtypeMapMarker extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new MapMarkerType();
  }

  public function getInputfieldType($type = null)
  {
  	return new InputfieldMapMarker();
  }

  public function setValue(Page $page, $value)
  {
  	$fieldName = $this->field->name;
    $page->$fieldName->lat = $value['lat'];
    $page->$fieldName->lng = $value['lng'];
    $page->$fieldName->address = $value['address'];
    $page->$fieldName->zoom = $value['zoom'];
  }

}