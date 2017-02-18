<?php

namespace ProcessWire\GraphQL\Type\Input\Inputfield;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\StringType;

class InputFieldMapMarker extends AbstractInputObjectType {

	public function build($config)
	{
		$config->addField('lat', new FloatType());
		$config->addField('lng', new FloatType());
		$config->addField('address', new stringType());
		$config->addField('zoom', new IntType());
	}

}