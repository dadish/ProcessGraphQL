<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\Page;

class FieldtypeThirdParty extends AbstractFieldtype {

	public function __construct($class, $field)
	{
		$this->class = $class;
		parent::__construct($field);
	}

	public function getName()
	{
		return $this->class::getName();
	}

	public function getDefaultType()
	{
		$this->class::getType();
	}

	public function getInputfieldType($type = null)
	{
		return $this->class::getInputType();
	}

	public function setValue(Page $page, $value)
	{
		return $this->class::setValue($page, $this->field, $value);
	}

}