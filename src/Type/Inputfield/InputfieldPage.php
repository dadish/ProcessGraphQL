<?php namespace ProcessWire\GraphQL\Type\Inputfield;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Page;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Utils;

class InputfieldPage
{
  public static function getName()
  {
    return 'InputfieldPage';
  }

  public static function getDescription($field = null)
  {
    $desc = '';
    if (!is_null($field)) {
      $desc = $field->description;
    }
    return $desc;
  }
  
  public static function type($field)
  {
    return Cache::type(self::getName(), function () use ($field) {
      return new InputObjectType([
        'name' => self::getName(),
        'fields' => [
          'add' => [
            'type' => Type::listOf(Type::id()),
            'description' => 'List of page ids that you would like to add.',
          ],
          'remove' => [
            'type' => Type::listOf(Type::id()),
            'description' => 'List of page ids that you would like to remove.',
          ]
        ],
      ]);
    });
  }

  public static function inputField($field)
  {
    return [
      'name' => $field->name,
      'description' => self::getDescription($field),
      'type' => self::type($field),
    ];
  }

  public static function setValue(Page $page, $field, $value)
  {
    $fieldName = $field->name;
    $fieldValue = $page->$fieldName;
    if (isset($value['add'])) {
      foreach ($value['add'] as $id) {
        $fieldValue->add(Utils::pages()->get($id));
      }
    }
    if (isset($value['remove'])) {
      foreach ($value['remove'] as $id) {
        $fieldValue->remove(Utils::pages()->get($id));
      }
    }
    $page->$fieldName = $fieldValue;
  }
}
