<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template as PWTemplate;
use ProcessWire\GraphQL\Type\PageType;
use ProcessWire\Pages as PWPages;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\ChromePhp;

class PageArrayType {
	public static $name = 'PageArray';

	public static $description = 'ProcessWire PageArray.';

	private static $types = [];

	public static function type(PWTemplate $template = null)
	{
		if ($template instanceof PWTemplate) {
			return self::templatedType($template);
		}

		self::$types[self::$name] = new ObjectType([
			'name' => self::$name,
			'description' => self::$description,
			'fields' => [
				'list' => [
					'type' => Type::listOf(PageType::type()),
					'description' => 'List of PW Pages.',
					'resolve' => function (PWPages $value) {
						return $value->find(SelectorType::parseValue(''));
					},
				],
			],
		]);

		return self::$types[self::$name];
	}

	public static function templatedType(PWTemplate $template)
	{
		if (isset(self::$types[$template->name])) {
			return self::$types[$template->name];
		}

		self::$types[$template->name] = new ObjectType([
			'name' => self::templatedTypeName($template),
			'description' => self::templatedTypeDescription($template),
			'fields' => [
				'list' => [
					'type' => Type::listOf(PageType::type()),
					'description' => "List of " . self::templatedTypeName($template),
					'resolve' => function (PWPageArray $value) use ($template) {
						return $value->find(SelectorType::parseValue("template=$template"));
					},
				]
			]
		]);

		return self::$types[$template->name];
	}

	public static function templatedTypeName(PWTemplate $template)
	{
		return $template->name;
	}

	public static function templatedTypeDescription(PWTemplate $template)
	{
		if ($template->description) {
			return $template->description;
		}

		return "PageArray with the template $template->name.";
	}
}
