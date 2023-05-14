<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType;
use ProcessWire\GraphQL\Cache;
use ProcessWire\InputfieldSelectMultiple;
use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\SetValueTrait;
use ProcessWire\GraphQL\Type\Fieldtype\Traits\FieldTrait;
use ProcessWire\Page;

class FieldtypeOptions
{
  use FieldTrait;
  use SetValueTrait;

  public static $name = 'FieldtypeOptions';

  public static $inputName = 'FieldtypeOptionsInput';

  public static $description = 'Field that stores single and multi select options.';

  public static function type($field)
  {
    $type = Cache::type(self::$name, function () use ($field) {
      return new ObjectType([
        'name' => self::$name,
        'description' => self::$description,
        'fields' => [
          [
            'name' => 'title',
            'type' => Type::nonNull(Type::string()),
            'description' => 'The title of the selected option.',
            'resolve' => function ($value) {
              return $value->title;
            }
          ],
          [
            'name' => 'value',
            'type' => Type::string(),
            'description' => 'The value of the selected option.',
            'resolve' => function ($value) {
              return $value->value;
            }
          ],
          [
            'name' => 'id',
            'type' => Type::nonNull(Type::int()),
            'description' => 'The id of the selected option.',
            'resolve' => function ($value) {
              return $value->id;
            }
          ]
        ],
      ]);
    });

    if (self::isMultiple($field)) {
      return Type::listOf($type);
    }

    return $type;
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
        'resolve' => function (Page $page, array $args) use ($field) {
          $fieldName = $field->name;

          // If a single option field does not have an id, then it means it's empty.
          if (!self::isMultiple($field) && !$page->$fieldName->id) {
            return null;
          }

          return $page->$fieldName;
        }
      ];
    });
  }


  public static function inputType($field)
  {
    return Cache::type(self::getName($field), function () use ($field) {
      $options = [];
      foreach ($field->type->getOptions($field) as $option) {
        $options[$option->title ? $option->title : $option->value] = [
          'value' => $option->title ? $option->title : $option->value,
          'description' => $option->value,
        ];
      }

      $type = new EnumType([
        'name' => self::getName($field),
        'description' => "Possible values for the ". self::$name .".",
        'values' => $options,
      ]);

      if (self::isMultiple($field)) {
        return Type::listOf($type);
      }

      return $type;
    });
  }

  public static function isMultiple($field)
  {
    $inputfieldClassName = 'ProcessWire\\' . $field->inputfieldClass;
    $inputfieldClassInstance = new $inputfieldClassName();
    $result = $inputfieldClassInstance instanceof InputfieldSelectMultiple;
    return $result;
  }

  public static function inputField($field)
  {
    return Cache::field("input--{$field->name}", function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::inputType($field),
      ];
    });
  }

  public static function getName(Field $field = null)
  {
    if ($field instanceof Field) {
      return Utils::normalizeTypeName("{$field->name}".self::$inputName);
    }

    return self::$inputName;
  }
}
