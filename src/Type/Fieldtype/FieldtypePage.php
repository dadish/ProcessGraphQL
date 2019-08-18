<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\Traits\CacheTrait;
use ProcessWire\GraphQL\Type\Traits\FieldTrait;
use ProcessWire\GraphQL\Type\Traits\InputFieldTrait;
use ProcessWire\GraphQL\Type\Traits\SetValueTrait;

class FieldtypePage
{
  use CacheTrait;
  use FieldTrait;
  use InputFieldTrait;
  use SetValueTrait;
  public static function type($field)
  {
    $template = null;
    // if template is chosen for the FieldtypePage
    // then we resolve to TemplatedPageArrayType
    if ($field->template_id) {
      $template = \ProcessWire\wire('templates')->get($field->template_id);
    }
      
    return PageArrayType::type($template);
  }
}
