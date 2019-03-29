<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\PWTypes;
use ProcessWire\GraphQL\Utils;
use ProcessWire\Page as PWPage;
use ProcessWire\NullPage;
use ProcessWire\WireData;
use ProcessWire\FieldtypeDatetime;

class Resolver
{
  private static $emptyUser;

  public static function resolveWithSelector(array $options)
  {
    return array_merge($options, [
      'args' => [
        's' => [
          'type' => PWTypes::selector(),
          'description' => "ProcessWire selector."
        ],
      ],
      'resolve' => function (PWPage $page, array $args) use ($options) {
        $name = $options['name'];
        if (isset($args['s'])) {
          return $page->$name($args['s']);
        }
        return $page->$name;
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
      'resolve' => function (PWPage $page, array $args) use ($options) {
        $name = $options['name'];
    
        if (isset($args['format'])) {
          $format = $args['format'];
          $rawValue = $page->$name;
          $field = Utils::fields()->get($name);
          if ($field && $field->type instanceof FieldtypeDatetime) {
            $rawValue = $page->getUnformatted($name);
          }
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
      'resolve' => function (PWPage $page) use ($options) {
        $name = $options['name'];
        $result = $page->$name;
        if ($result instanceof NullPage) {
          return self::getEmptyUser();
        }
        if ($result instanceof Page) {
          $templateName = $result->template->name;
          if (Utils::moduleConfig()->legalViewTemplates->find("name=$templateName")->count()) {
            return $result;
          } else {
            return self::getEmptyUser();
          }
        }
        return $result;
      }
    ]);
  }
}
