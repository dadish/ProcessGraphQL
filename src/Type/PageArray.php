<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\Page;
use ProcessWire\Pages as PWPages;

class PageArray {
	public static function type()
	{
		return new ObjectType([
			'name' => self::getName(),
			'description' => self::getDescription(),
			'fields' => [
				'list' => [
					'type' => Type::listOf(Page::type()),
					'description' => 'List of PW Pages.',
					'resolve' => function(PWPages $pages) {
						return $pages->find('numChildren>10, limit=1, template=city');
					},
				],
			],
		]);
	}

	public static function getName()
	{
		return 'PageArray';
	}

	public static function getDescription()
	{
		return 'ProcessWire PageArray.';
	}

	public static function asField()
	{
		return [
			'name' => 'pages',
			'type' => self::type(),
			'description' => 'ProcessWire Pages',
			'resolve' => function (PWPages $pages) {
				return $pages;
			}
		];
	}
}
