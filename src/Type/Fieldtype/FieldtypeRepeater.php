<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\Page;
use ProcessWire\GraphQL\Type\Inputfield\InputfieldRepeater;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypeRepeater
{ 
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;

  public static $name = 'Repeater';

  public static $description = 'Maintains a collection of fields that are repeated for any number of times.';

  public static function type($field)
  {
    $templateId = $field->get('template_id');
    $template = Utils::templates()->get($templateId);
    return PageArrayType::type($template);
  }

  public static function inputField($field)
  {
    return InputfieldRepeater::inputField($field);
  }

  public static function setValue(Page $page, $field, $value)
  {
    return InputfieldRepeater::setValue($page, $field, $value);
  }
}
