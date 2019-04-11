<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Page as PWPage;
use ProcessWire\GraphQL\Type\Resolver;

class PageType
{
  public static $name = 'Page';

  public static $description = 'ProcessWire Page.';

  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    $selfType = null;
    $selfType = new ObjectType([
      'name' => self::$name,
      'description' => self::$description,
      'fields' => function () use (&$selfType) {
        return self::getBuiltInFields($selfType);
      },
    ]);
    
    self::$type = $selfType;
    return self::$type;
  }

  public static function getBuiltInFields($selfType)
  {
    return [

      Resolver::resolveWithSelector([
        'name' => 'child',
        'type' => $selfType,
        'description' => "The first child of this page. If the `s`(selector) argument is provided then the 
                          first matching child (subpage) that matches the given selector. Returns a Page or null.",
      ]),

      Resolver::resolveWithSelector([
        'name' => 'children',
        'type' => Type::listOf($selfType),
        'description' => "The number of children (subpages) this page has, optionally limiting to visible 
                          pages. When argument `visible` true, number includes only visible children 
                          (excludes unpublished, hidden, no-access, etc.)",
      ]),

      Resolver::resolveWithDateFormatter([
        'name' => 'created',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Date of when the page was created.',
      ]),

      Resolver::resolveUser([
        'name' => 'createdUser',
        'type' => PWTypes::user(),
        'description' => 'The user that created this page.',
      ]),

      [
        'name' => 'httpUrl',
        'type' => Type::string(),
        'description' => 'Same as `url`, except includes protocol (http or https) and hostname.',
      ],

      [
        'name' => 'id',
        'type' => Type::id(),
        'description' => 'ProcessWire Page id.',
      ],

      Resolver::resolveWithDateFormatter([
        'name' => 'modified',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Date of when the page was last modified.',
      ]),

      Resolver::resolveUser([
        'name' => 'modifiedUser',
        'type' => PWTypes::user(),
        'description' => 'The user that last modified this page.',
      ]),

      [
        'name' => 'name',
        'type' => Type::string(),
        'description' => 'ProcessWire Page name.',
      ],

      [
        'name' => 'numChildren',
        'type' => Type::int(),
        'description' => "The number of children (subpages) this page has, optionally limiting to 
                          visible pages. When argument `visible` true, number includes only visible 
                          children (excludes unpublished, hidden, no-access, etc.)",
        'args' => [
          'visible' => [
            'type' => Type::boolean(),
          ],
        ],
        'resolve' => function (PWPage $page, array $args) {
          $visible = false;
          if (isset($args['visible'])) {
            $visible = $args['visible'];
          }
          if ($visible) {
            return $page->numChildren($visible);
          }
          return $page->numChildren;
        },
      ],

      Resolver::resolveWithSelector([
        'name' => 'parent',
        'type' => $selfType,
        'description' => 'The parent Page object, or the closest parent matching the given selector. Returns `null` if there is no parent or no match.'
      ]),

      [
        'name' => 'parentID',
        'type' => Type::string(),
        'description' => 'The numbered ID of the parent page or 0 if none.',
      ],

      Resolver::resolveWithSelector([
        'name' => 'parents',
        'type' => Type::listOf($selfType),
        'description' => "Return this page's parent pages as PageArray. Optionally filtered by a selector.",
      ]),

      [
        'name' => 'path',
        'type' => Type::string(),
        'description' => "The page's URL path from the homepage (i.e. /about/staff/ryan/)",
      ],

      [
        'name' => 'rootParent',
        'type' => Type::nonNull($selfType),
        'description' => 'The parent page closest to the homepage (typically used for identifying a section)',
      ],

      [
        'name' => 'template',
        'type' => Type::string(),
        'description' => 'Template name of the page.',
      ],

      [
        'name' => 'url',
        'type' => Type::string(),
        'description' => "The page's URL path from the server's document root (may be the same as the `path`)",
      ],
    ];
  }
}
