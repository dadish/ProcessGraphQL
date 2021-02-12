<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Page;
use ProcessWire\NullPage;
use ProcessWire\Template;
use ProcessWire\WireData;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Permissions;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\UserType;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\SelectorType;

class PageType
{
  private static $emptyUser;

  public static function &type($template = null)
  {
    $type = null;
    if ($template instanceof Template) {
      $type =& self::templateType($template);
    } else {
      $type =& Cache::type(self::getName(), function () use ($type) {
        return new InterfaceType([
          'name' => self::getName(),
          'description' => self::getDescription(),
          'fields' => function () {
            return self::getLegalBuiltInFields();
          },
          'resolveType' => function($value) use ($type) {
            $resolvedType = $type;
            if ($value->id && $value->template instanceof Template) {
              $resolvedType =& self::templateType($value->template);
            }
            return $resolvedType;
          }
        ]);
      });
    }

    return $type;
  }

  public static function getBuiltInFields()
  {
    $type =& self::type();
    return [
      self::resolvePagefieldWithSelector([
        'name' => 'child',
        'type' => $type,
        'description' => "The first child of this page. If the `s`(selector) argument is provided then the 
                          first matching child (subpage) that matches the given selector. Returns a Page or null.",
      ]),

      self::resolvePagefieldWithSelector([
        'name' => 'children',
        'type' => PageArrayType::type(),
        'description' => "The number of children (subpages) this page has, optionally limiting to visible 
                          pages. When argument `visible` true, number includes only visible children 
                          (excludes unpublished, hidden, no-access, etc.)",
      ]),

      self::resolveWithDateFormatter([
        'name' => 'created',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Date of when the page was created.',
      ]),

      self::resolveUser([
        'name' => 'createdUser',
        'type' => Type::nonNull(UserType::type()),
        'description' => 'The user that created this page.',
      ]),

      [
        'name' => 'httpUrl',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Same as `url`, except includes protocol (http or https) and hostname.',
      ],

      [
        'name' => 'id',
        'type' => Type::nonNull(Type::id()),
        'description' => 'ProcessWire Page id.',
      ],

      self::resolveWithDateFormatter([
        'name' => 'modified',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Date of when the page was last modified.',
      ]),

      self::resolveUser([
        'name' => 'modifiedUser',
        'type' => Type::nonNull(UserType::type()),
        'description' => 'The user that last modified this page.',
      ]),

      [
        'name' => 'name',
        'type' => Type::nonNull(Type::string()),
        'description' => 'ProcessWire Page name.',
      ],

      [
        'name' => 'numChildren',
        'type' => Type::nonNull(Type::int()),
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

      self::resolvePagefieldWithSelector([
        'name' => 'parent',
        'type' => $type,
        'description' => 'The parent Page object, or the closest parent matching the given selector. Returns `null` if there is no parent or no match.'
      ]),

      [
        'name' => 'parentID',
        'type' => Type::nonNull(Type::id()),
        'description' => 'The numbered ID of the parent page or 0 if none.',
      ],

      self::resolvePagefieldWithSelector([
        'name' => 'parents',
        'type' => Type::nonNull(PageArrayType::type()),
        'description' => "Return this page's parent pages as PageArray. Optionally filtered by a selector.",
      ]),

      [
        'name' => 'path',
        'type' => Type::nonNull(Type::string()),
        'description' => "The page's URL path from the homepage (i.e. /about/staff/ryan/)",
      ],

      [
        'name' => 'template',
        'type' => Type::nonNull(Type::string()),
        'description' => 'Template name of the page.',
      ],

      [
        'name' => 'url',
        'type' => Type::nonNull(Type::string()),
        'description' => "The page's URL path from the server's document root (may be the same as the `path`)",
      ],
      
      self::resolvePagefieldWithSelector([
        'name' => 'references',
        'type' => PageArrayType::type(),
        'description' => "Return pages that have Page reference fields pointing to this one (references)",
      ])
      
    ];
  }

  public static function getLegalBuiltInFields()
  {
    $builtInFields = self::getBuiltInFields();
    return array_filter($builtInFields, function ($field) {
      return in_array($field['name'], Utils::module()->legalPageFields);
    });
  }

  public static function &templateType(Template $template)
  {
    $temlpateType =& Cache::type(self::getName($template), function () use ($template) {
      $type =& self::type();
      return new ObjectType([
        'name' => self::getName($template),
        'description' => self::getDescription($template),
        'fields' => function () use ($template) {
          return self::getFields($template);
        },
        'interfaces' => [
          $type
        ]
      ]);
    });
    return $temlpateType;
  }

  public static function getFields(Template $template)
  {
    $fields = [];

    // add the template fields
    $legalFields = Utils::module()->legalFields;
    foreach ($template->fields as $field) {
      // skip illigal fields
      if (!in_array($field->name, $legalFields)) {
        continue;
      }

      // check if user has permission to view this field
      if (!Permissions::canViewField($field, $template)) {
        continue;
      }

      $fieldClass = Utils::pwFieldToGraphqlClass($field);
      if (is_null($fieldClass)) {
        continue;
      }

      $fieldSettings = $fieldClass::field($field);
      if ($field->required) {
        $fieldSettings['type'] = Type::nonNull($fieldSettings['type']);
      }
      $fields[] = $fieldSettings;
    }

    // add all the built in page fields
    foreach (self::type()->getFields() as $field) {
      $fields[] = $field;
    }

    return $fields;
  }

  public static function getName(Template $template = null)
  {
    if ($template instanceof Template) {
      return Utils::normalizeTypeName("{$template->name}Page");
    }

    return 'Page';
  }

  public static function getDescription(Template $template = null)
  {
    if ($template instanceof Template) {
      $desc = $template->description;
      if ($desc) return $desc;
      return "PageType with template `" . $template->name . "`.";
    }
    return 'ProcessWire Page.';
  }

  public static function resolvePagefieldWithSelector(array $options)
  {
    return array_merge($options, [
      'args' => [
        's' => [
          'type' => SelectorType::type(),
          'description' => "ProcessWire selector."
        ],
      ],
      'resolve' => function (Page $page, array $args) use ($options) {
        $name = $options['name'];
        $selector = "";
        if (isset($args['s'])) {
          $selector = SelectorType::parseValue($args['s']);
        } else {
          $selector = SelectorType::parseValue("");
        }
        $result = $page->$name($selector);
        if ($result instanceof NullPage) return null;
        return $result;
      }
    ]);
  }

  public static function getEmptyUser()
  {
    if (self::$emptyUser instanceof WireData) {
      return self::$emptyUser;
    }
    $value = new WireData();
    $value->name = '';
    $value->email = '';
    $value->id = '';

    self::$emptyUser = $value;

    return $value;
  }

  public static function resolveUser(array $options)
  {
    return array_merge($options, [
      'resolve' => function (Page $page) use ($options) {
        $name = $options['name'];
        $result = $page->$name;
        if ($result instanceof NullPage) {
          return self::getEmptyUser();
        }
        if ($result instanceof Page) {
          $templateName = $result->template->name;
          if (Permissions::getViewTemplates()->find("name=$templateName")->count()) {
            return $result;
          } else {
            return self::getEmptyUser();
          }
        }
        return $result;
      }
    ]);
  }

  public static function resolveWithDateFormatter(array $options)
  {
    return array_merge($options, [
      'args' => [
        'format' => [
          'type' => Type::string(),
          'description' => "PHP date formatting string. Refer to https://devdocs.io/php/function.date",
        ],
      ],
      'resolve' => function (Page $page, array $args) use ($options) {
        $name = $options['name'];
    
        if (isset($args['format'])) {
          $format = $args['format'];
          $rawValue = $page->$name;
          if ($rawValue) {
            return date($format, $rawValue);
          } else {
            return "";
          }
        }
        
        return $page->$name;
      }
    ]);
  }
}
