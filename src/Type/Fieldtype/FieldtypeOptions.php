<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\InputfieldSelectMultiple;
use GraphQL\Type\Definition\EnumType;

class FieldtypeOptions
{ 
  use CacheTrait;
  public static function type()
  {
    return self::cache('default', function () {
      return new ObjectType([
        'name' => 'Options',
        'description' => 'Field that stores single and multi select options.',
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
  }

  public static function isMultiple($field)
  {
    $inputfieldClassName = 'ProcessWire\\' . $field->inputfieldClass;
    $inputfieldClassInstance = new $inputfieldClassName();
    $result = $inputfieldClassInstance instanceof InputfieldSelectMultiple;
    return $result;
  }

  public static function field($field)
  {
    return self::cache("field-{$field->name}", function () use ($field) {
      // description
      $desc = $field->description;
      if (!$desc) {
        $desc = "Field with the type of {$field->type}";
      }

      return [
        'name' => $field->name,
        'description' => $desc,
        'type' => self::isMultiple($field) ? Type::listOf(self::type()) : self::type(),
      ];
    });
  }

  public function inputType($field)
  {
    return self::cache("input-field-{$field->name}", function () use ($field) {
      $options = [];
      foreach ($field->type->getOptions($field) as $option) {
        $options[] = [
          'value' => $option->value ? $option->value : $option->title,
          'name' => $option->title,
        ];
      }

      $type = new EnumType([
        'name' => $field->name,
        'description' => "Possible values for the `{$field->name}`.",
        'values' => $options,
      ]);

      if (self::isMultiple($field)) {
        return Type::listOf($type);
      }

      return $type;
    });
  }
}
