<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\Page;

class FieldtypeThirdParty extends AbstractFieldtype {

	protected $thirdPartyClass;

	public function __construct($thirdPartyClass, $field)
	{
		$this->thirdPartyClass = $thirdPartyClass;
		parent::__construct($field);
	}

	public function getDefaultType()
	{
		return $this->thirdPartyClass::getType();
	}

	public function getInputfieldType($type = null)
	{
		return $this->thirdPartyClass::getInputType();
	}

	public function setValue(Page $page, $value)
	{
		return $this->thirdPartyClass::setValue($page, $this->field, $value);
	}

}