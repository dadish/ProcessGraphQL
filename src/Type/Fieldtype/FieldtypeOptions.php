<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType;
use ProcessWire\GraphQL\Cache;
use ProcessWire\InputfieldSelectMultiple;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;

class FieldtypeOptions
{ 
  use FieldTrait;
  use SetValueTrait;

  public static $name = 'FieldtypeOptions';

  public static $inputName = 'FieldtypeOptionsInput';

  public static $description = 'Field that stores single and multi select options.';

  public static function type($field)
  {
    return Cache::type(self::$name, function () use ($field) {
      $type = new ObjectType([
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

      if (self::isMultiple($field)) {
        return Type::listOf($type);
      }

      return $type;
    });
  }

  public static function inputType($field)
  {
    return Cache::type(self::$inputName, function () use ($field) {
      $options = [];
      foreach ($field->type->getOptions($field) as $option) {
        $options[$option->title ? $option->title : $option->value] = [
          'value' => $option->title ? $option->title : $option->value,
          'description' => $option->value,
        ];
      }

      $type = new EnumType([
        'name' => self::$inputName,
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
        'type' => $field->required ? Type::nonNull(self::inputType($field)) : self::inputType($field),
      ];
    });
  }
}
