<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphqL\Execution\ResolveInfo;
use ProcessWire\Template;
use ProcessWire\FieldtypePage as PWFieldtypePage;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Type\Object\TemplatedPageArrayType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypePage extends AbstractFieldtype {

  public function getType()
  {
    // if template is chosen for the FieldtypePage
    // then we resolve to TemplatedPageArrayType
    if ($this->field->template_id) {
      $template = \ProcessWire\wire('templates')->get($this->field->template_id);
      if ($template instanceof Template) return new TemplatedPageArrayType($template);
    }
      
    return new PageArrayType();   
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    $field = \ProcessWire\wire('fields')->get($fieldName);
    $field->derefAsPage = PWFieldtypePage::derefAsPageArray;
    return $value->$fieldName;
  }

}