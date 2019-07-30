<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\InputfieldSelectMultiple;

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

  public static function field($options, $field)
  {
    return self::cache('field-' . $options['name'], function () use ($options, $field) {
      return array_merge($options, [
        'type' => self::isMultiple($field) ? Type::listOf(self::type()) : self::type(),
      ]);
    });
  }
}
