<?php namespace ProcessWire\GraphQL\Type\Traits;

use ProcessWire\Page;

trait SetValueTrait
{
  public static function setValue(Page $page, $field, $value)
  {
    $fieldName = $field->name;
    $page->$fieldName = $value;
  }
}
