<?php namespace ProcessWire\GraphQL;

use Youshido\GraphQL\Type\ListType\AbstractListType;
use Youshido\GraphQL\Type\Scalar\IdType;

class FieldtypePage extends AbstractListType {

	public function getItemType()
	{
		return new IdType();
	}

}
