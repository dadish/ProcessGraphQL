<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Template;
use ProcessWire\GraphQL\Type\PageType;
use ProcessWire\GraphQL\Type\SelectorType;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Utils;

class PageArrayType {

	use CacheTrait;

	public static $name = 'PageArray';

	public static $description = 'ProcessWire PageArray.';

	public static function &type(Template $template = null)
	{
		$type = null;
		if ($template instanceof Template) {
			$type =& self::templateType($template);
		} else {
			$type =& self::cache('default', function () {
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
						'first' => [
							'type' => PageType::type(),
							'description' => 'Returns the first item in the WireArray.',
							'resolve' => function ($value) {
								return $value->find(SelectorType::parseValue(''))->first();
							},
						],
						'last' => [
							'type' => PageType::type(),
							'description' => 'Returns the last item in the WireArray.',
							'resolve' => function ($value) {
								return $value->find(SelectorType::parseValue(''))->last();
							},
						]
					],
				]);
			});
		}
		return $type;
	}

	public static function &templateType(Template $template)
	{
		$type =& self::cache('PageArrayType--' . Utils::getTemplateCacheKey($template), function () use ($template) {
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
					],
					'first' => [
						'type' => PageType::type($template),
						'description' => 'Returns the first item in the WireArray.',
						'resolve' => function ($value) use ($template) {
							return $value->find(SelectorType::parseValue("template=$template"))->first();
						},
					],
					'last' => [
						'type' => PageType::type($template),
						'description' => 'Returns the last item in the WireArray.',
						'resolve' => function ($value) use ($template) {
							return $value->find(SelectorType::parseValue("template=$template"))->last();
						},
					]
				]
			]);
		});
		return $type;
	}

	public static function templatedTypeName(Template $template)
	{
		return $template->name;
	}

	public static function templatedTypeDescription(Template $template)
	{
		if ($template->description) {
			return $template->description;
		}

		return "Pages with the template $template->name.";
	}

	public static function field(Template $template)
	{
		$type =& self::type($template);
		return Resolver::resolvePageArray([
			'name' => self::templatedTypeName($template),
			'description' => self::templatedTypeDescription($template),
			'type' => $type,
		]);
	}
}
