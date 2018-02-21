<?php

namespace ProcessWire\GraphQL\Type\Enum;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;
use ProcessWire\Field;

class SelectableOptionEnumType extends AbstractEnumType {

	function __construct(Field $field)
	{
		$this->field = $field;
		parent::__construct([]);
	}

	public function getValues()
	{
		$options = [];
		foreach ($this->field->type->getOptions($this->field) as $option) {
			$options[] = [
				'value' => $option->value || $option->title,
				'name' => $option->title,
			];
		}
		return $options;
	}

}