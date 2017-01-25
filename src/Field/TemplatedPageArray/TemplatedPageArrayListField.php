<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\GraphQL\Traits\TemplateAwareTrait;
use ProcessWire\GraphQL\Field\PageArray\PageArrayListField;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;

class TemplatedPageArrayListField extends PageArrayListField {

  use TemplateawareTrait;

  public function getType()
  {
    return new ListType(new TemplatedPageType($this->template));
  }

}