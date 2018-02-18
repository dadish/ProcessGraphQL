<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\SelectableOptionType;
use ProcessWire\InputfieldSelectMultiple;

class FieldtypeOptions extends AbstractFieldtype {

  public function getDefaultType()
  {
  	$inputfieldClassName = 'ProcessWire\\' . $this->field->inputfieldClass;
    $inputfieldClassInstance = new $inputfieldClassName();
    if ($inputfieldClassInstance instanceof InputfieldSelectMultiple) {
      return new ListType(new SelectableOptionType());
    }
    return new SelectableOptionType();
  }

}