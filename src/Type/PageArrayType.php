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
					'fields' => array_merge(self::getPaginationFields(), [
						[
							'name' => 'list',
							'type' => Type::listOf(PageType::type()),
							'description' => 'List of PW Pages.',
							'resolve' => function ($value) {
								return $value;
							},
						],
						[
							'name' => 'first',
							'type' => PageType::type(),
							'description' => 'Returns the first item in the WireArray.',
							'resolve' => function ($value) {
								return $value->first();
							},
						],
						[
							'name' => 'last',
							'type' => PageType::type(),
							'description' => 'Returns the last item in the WireArray.',
							'resolve' => function ($value) {
								return $value->last();
							},
						]
					]),
				]);
			});
		}
		return $type;
	}

	public static function &templateType(Template $template)
	{
		$type =& self::cache('PageArrayType--' . PageType::getTemplateCacheKey($template), function () use ($template) {
			return new ObjectType([
				'name' => self::templatedTypeName($template),
				'description' => self::templatedTypeDescription($template),
				'fields' => array_merge(self::getPaginationFields(), [
					[
						'name' => 'list',
						'type' => Type::listOf(PageType::type($template)),
						'description' => "List of " . self::templatedTypeName($template),
						'resolve' => function ($value) {
							return $value;
						},
					],
					[
						'name' => 'first',
						'type' => PageType::type($template),
						'description' => 'Returns the first item in the WireArray.',
						'resolve' => function ($value) use ($template) {
							return $value->first();
						},
					],
					[
						'name' => 'last',
						'type' => PageType::type($template),
						'description' => 'Returns the last item in the WireArray.',
						'resolve' => function ($value) use ($template) {
							return $value->last();
						},
					],
				])
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
		return [
			'name' => self::templatedTypeName($template),
			'description' => self::templatedTypeDescription($template),
			'type' => $type,
      'args' => [
        's' => [
          'type' => SelectorType::type(),
          'description' => "ProcessWire selector."
        ],
      ],
      'resolve' => function ($pages, array $args) use ($template) {
				$selector = "";
				if ($template) {
					$selector .= "template=$template, ";
				}
        if (isset($args['s'])) {
          $selector .= $args['s'] . ", ";
				}
				rtrim($selector, ", ");
				return $pages->find(SelectorType::parseValue($selector));
      }
    ];
	}

	public static function getPaginationFields()
	{
		$maxLimit = Utils::moduleConfig()->maxLimit;
		return [
			[
				'name' => 'getTotal',
				'type' => Type::int(),
				'description' => 'Get the total number of pages that were found from a $pages->find("selectors, limit=n")
													operation that led to this PageArray. The number returned may be greater than the number
													of pages actually in PageArray, and is used for calculating pagination.
													Whereas `count` will always return the number of pages actually in PageArray.',
				'resolve' => function ($value) {
					return (integer) $value->getTotal();
				}
			],
			[
				'name' => 'getLimit',
				'type' => Type::int(),
				'description' => "Get the number (n) from a 'limit=n' portion of a selector that resulted in the PageArray.
													In pagination, this value represents the max items to display per page. The default limit
													is set to $maxLimit.",
				'resolve' => function ($value) {
					return (integer) $value->getLimit();
				}
			],
			[
				'name' => 'getStart',
				'type' => Type::int(),
				'description' => "Get the number of the starting result that led to the PageArray in pagination.
													Returns 0 if in the first page of results.",
				'resolve' => function ($value) {
					return (integer) $value->getStart();
				}
			]
		];
	}
}
