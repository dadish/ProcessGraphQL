<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template as PWTemplate;
use ProcessWire\GraphQL\Type\PageType;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\CacheTrait;

class PageArrayType {

	use CacheTrait;

	public static $name = 'PageArray';

	public static $description = 'ProcessWire PageArray.';

	public static function buildType()
	{
		return new ObjectType([
			'name' => self::$name,
			'description' => self::$description,
			'fields' => [
				'list' => [
					'type' => Type::listOf(PageType::type()),
					'description' => 'List of PW Pages.',
					'resolve' => function ($value) {
						return $value->find(SelectorType::parseValue(''));
					},
				],
			],
		]);
	}

	public static function buildTemplateType(PWTemplate $template)
	{
		return new ObjectType([
			'name' => self::templatedTypeName($template),
			'description' => self::templatedTypeDescription($template),
			'fields' => [
				'list' => [
					'type' => Type::listOf(PageType::type($template)),
					'description' => "List of " . self::templatedTypeName($template),
					'resolve' => function ($value) use ($template) {
						return $value->find(SelectorType::parseValue("template=$template"));
					},
				]
			]
		]);
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

		return "Pages with the template $template->name.";
	}

	public static function asField(PWTemplate $template)
	{
		return Resolver::resolvePageArray([
			'name' => self::templatedTypeName($template),
			'description' => self::templatedTypeDescription($template),
			'type' => self::type($template),
		]);
	}
}
