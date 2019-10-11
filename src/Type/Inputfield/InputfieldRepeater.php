<?php namespace ProcessWire\GraphQL\Type\Inputfield;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\Page;
use ProcessWire\NullPage;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\InputType\PageCreateInputType;
use ProcessWire\GraphQL\InputType\RepeaterCreateInputType;
use ProcessWire\GraphQL\InputType\RepeaterUpdateInputType;
use ProcessWire\GraphQL\Utils;

class InputfieldRepeater
{
  public static function getName($field)
  {
    return Utils::normalizeTypeName("{$field->name}RepeaterInput");
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
    return Cache::type(self::getName($field), function () use ($field) {
      $templateId = $field->get('template_id');
      $template = Utils::templates()->get($templateId);
      return new InputObjectType([
        'name' => self::getName($field),
        'fields' => [
          'add' => [
            'type' => Type::listOf(RepeaterCreateInputType::type($template)),
            'description' => 'List of values that you want to add into your repeater field.',
          ],
          'remove' => [
            'type' => Type::listOf(Type::id()),
            'description' => "List of repeater items' ids that you would like to remove.",
          ],
          'update' => [
            'type' => Type::listOf(RepeaterUpdateInputType::type($template)),
            'description' => 'List of values for repeater items that you would like to update.',
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
    if (isset($value['add'])) {
      foreach ($value['add'] as $createValues) {
        $r = $page->$fieldName->getNew();
        PageCreateInputType::setValues($r, $createValues);
        $r->save();
        $page->$fieldName->add($r);
      }
    }
    if (isset($value['remove'])) {
      foreach ($value['remove'] as $id) {
        $r = $page->$fieldName->get("id=$id");
        $page->$fieldName->remove($r);
      }
    }
    if (isset($value['update'])) {
      foreach ($value['update'] as $updateValues) {
        $id = $updateValues['id'];
        $r = $page->$fieldName->get("id=$id");

        // skip if the repeater item cannot be found
        if ($r instanceof NullPage) {
          continue;
        }
        // unset the id from updateValues
        unset($updateValues['id']);

        // set the new values
        PageCreateInputType::setValues($r, $updateValues);

        // save the repeater
        $r->save();
      }
    }
  }
}
