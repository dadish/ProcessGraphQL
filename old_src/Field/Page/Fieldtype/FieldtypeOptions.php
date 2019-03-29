<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\SelectableOptionType;
use ProcessWire\GraphQL\Type\Enum\SelectableOptionEnumType;
use ProcessWire\InputfieldSelectMultiple;

class FieldtypeOptions extends AbstractFieldtype {

  public function isMultiple()
  {
    $inputfieldClassName = 'ProcessWire\\' . $this->field->inputfieldClass;
    $inputfieldClassInstance = new $inputfieldClassName();
    return $inputfieldClassInstance instanceof InputfieldSelectMultiple;
  }

  public function getDefaultType()
  {
    if ($this->isMultiple()) {
      return new ListType(new SelectableOptionType());
    }
    return new SelectableOptionType();
  }

  public function getInputfieldType($type = null)
  {
    if ($this->isMultiple()) {
      return new ListType(new SelectableOptionEnumType($this->field));
    }
    return new SelectableOptionEnumType($this->field);
  }

}