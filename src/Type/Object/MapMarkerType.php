<?php

namespace ProcessWire\GraphQL\Type\Object;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\FloatType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class MapMarkerType extends AbstractObjectType {

	public function getName()
	{
		return 'MapMarker';
	}

	public function getDescription()
	{
		return 'Represents the `FieldtypeMapMarker` value.';
	}

	public function build($config)
	{
		$config->addField('lat',[
			'type' => new FloatType(),
			'description' => 'The latitude of the MapMarker.',
			'resolve' => function ($value) {
				return (float) $value->lat;
			}
		]);

		$config->addField('lng',[
			'type' => new FloatType(),
			'description' => 'The longitude of the MapMarker.',
			'resolve' => function ($value) {
				return (float) $value->lng;
			}
		]);

		$config->addField('address',[
			'type' => new StringType(),
			'description' => 'The address of the MapMarker.',
			'resolve' => function ($value) {
				return (string) $value->adress;
			}
		]);

		$config->addField('zoom',[
			'type' => new IntType(),
			'description' => 'The zoom of the MapMarker.',
			'resolve' => function ($value) {
				return (integer) $value->zoom;
			}
		]);
	}

}