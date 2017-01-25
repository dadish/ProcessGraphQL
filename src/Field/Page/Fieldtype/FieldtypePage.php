<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphqL\Execution\ResolveInfo;
use ProcessWire\Template;
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
    // first we turn of output formatting because we handle
    // FeildtypePage as a PageArray/WireArray
    $value->of(false);

    // get the desired value
    $fieldName = $this->field->name;
    $images = $value->$fieldName;

    // turn the output formatting back on so that other fieldtypes
    // are handled properly
    $value->of(true);

    // return the value
    return $images;
  }

}