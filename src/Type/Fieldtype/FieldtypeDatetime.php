<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\Page;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\CustomScalarType;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;

class FieldtypeDatetime
{ 
  use InputFieldTrait;

  public static $name = 'DateTime';

  public static $description = 'A string that represends a date and optionally time.';

  public static function type()
  {
    return Cache::type(self::$name, function () {
      return new CustomScalarType([
        'name' => self::$name,
        'description' => self::$description,
        'serialize' => function ($value) {
          return (string) $value;
        },
        'parseValue' => function ($value) {
          return (string) $value;
        },
        'parseLiteral' => function ($valueNode) {
          return (string) $valueNode->value;
        },
      ]);
    });
  }

  public static function field($field)
  {
    return Cache::field($field->name, function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::type($field),
        'args' => [
          'format' => [
            'type' => Type::string(),
            'description' => "PHP date formatting string. Refer to https://devdocs.io/php/function.date",
          ],
        ],
        'resolve' => function (Page $page, array $args) use ($field) {
          $name = $field->name;
      
          if (isset($args['format'])) {
            $format = $args['format'];
            $rawValue = $page->getUnformatted($name);
            if ($rawValue) {
              return date($format, $rawValue);
            } else {
              return "";
            }
          }
          
          return $page->$name;
        }
      ];
    });
  }

  public static function setValue(Page $page, $field, $value)
  {
  	$fieldName = $field->name;
  	$page->$fieldName = $value->format('Y-m-d H:i:s');
  }
}
