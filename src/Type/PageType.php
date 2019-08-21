<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Page;
use ProcessWire\Template;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\Resolver;
use ProcessWire\GraphQL\Type\UserType;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;

class PageType
{
  use CacheTrait;

  public static $name = 'Page';

  public static $description = 'ProcessWire Page.';

  public static function &type($template = null)
  {
    $type = null;
    if ($template instanceof Template) {
      $type =& self::templateType($template);
    } else {
      $type =& self::cache(self::getCacheKey(), function () {
        return new ObjectType([
          'name' => self::$name,
          'description' => self::$description,
          'fields' => function () {
            return self::getBuiltInFields();
          },
        ]);
      });
    }

    return $type;
  }

  public static function getBuiltInFields()
  {
    $type =& self::type();
    $builtInFields = [

      Resolver::resolvePagefieldWithSelector([
        'name' => 'child',
        'type' => $type,
        'description' => "The first child of this page. If the `s`(selector) argument is provided then the 
                          first matching child (subpage) that matches the given selector. Returns a Page or null.",
      ]),

      Resolver::resolvePagefieldWithSelector([
        'name' => 'children',
        'type' => PageArrayType::type(),
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
        'type' => UserType::type(),
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
        'type' => UserType::type(),
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
        'resolve' => function (Page $page, array $args) {
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

      Resolver::resolvePagefieldWithSelector([
        'name' => 'parent',
        'type' => $type,
        'description' => 'The parent Page object, or the closest parent matching the given selector. Returns `null` if there is no parent or no match.'
      ]),

      [
        'name' => 'parentID',
        'type' => Type::string(),
        'description' => 'The numbered ID of the parent page or 0 if none.',
      ],

      Resolver::resolvePagefieldWithSelector([
        'name' => 'parents',
        'type' => PageArrayType::type(),
        'description' => "Return this page's parent pages as PageArray. Optionally filtered by a selector.",
      ]),

      [
        'name' => 'path',
        'type' => Type::string(),
        'description' => "The page's URL path from the homepage (i.e. /about/staff/ryan/)",
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

    return array_filter($builtInFields, function ($field) {
      return in_array($field['name'], Utils::moduleConfig()->legalPageFields);
    });
  }

  public static function &templateType(Template $template)
  {
    $temlpateType =& self::cache('PageType--' . self::getTemplateCacheKey($template), function () use ($template) {
      return new ObjectType([
        'name' => self::getName($template),
        'description' => self::getDescription($template),
        'fields' => function () use ($template) {
          return self::getFields($template);
        },
      ]);
    });
    return $temlpateType;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // add the template fields
    $legalFields = Utils::moduleConfig()->legalFields;
    foreach ($template->fields as $field) {
      // skip illigal fields
      if (!in_array($field->name, $legalFields)) {
        continue;
      }

      // check if user has permission to view this field
      if (!Utils::hasFieldPermission('view', $field, $template)) {
        continue;
      }

      $fieldClass = Utils::pwFieldToGraphqlClass($field);
      if (is_null($fieldClass)) {
        continue;
      }

      $fields[] = $fieldClass::field($field);
    }

    // add all the built in page fields
    foreach (self::type()->getFields() as $field) {
      $fields[] = $field;
    }

    return $fields;
  }

  public Static function normalizeName($name)
  {
    return str_replace('-', '_', $name);
  }

  public static function getName(Template $template = null)
  {
    if ($template instanceof Template) {
      return ucfirst(self::normalizeName($template->name)) . 'Page';
    }

    return self::$name;
  }

  public static function getDescription(Template $template = null)
  {
    if ($template instanceof Template) {
      $desc = $template->description;
      if ($desc) return $desc;
      return "PageType with template `" . $template->name . "`.";
    }
    return self::$description;
  }

  public static function getCacheKey()
  {
    $key = '';
    $seperator = '-';

    // user's roles
    $key .= Utils::user()->roles->implode($seperator, 'name');

    // allowed fields
    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageFields);

    return $key;    
  }

  public static function getTemplateCacheKey(Template $template)
  {
    $key = self::getCacheKey();
    $seperator = '-';

    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalFields);

    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageFileFields);

    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageImageFields);

    // prepend template name
    return "{$template->name}--{$key}";
  }
}
