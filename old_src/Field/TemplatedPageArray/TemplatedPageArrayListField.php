<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\Template;
use ProcessWire\GraphQL\Field\PageArray\PageArrayListField;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;

class TemplatedPageArrayListField extends PageArrayListField {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public function getType()
  {
    return new ListType(new TemplatedPageType($this->template));
  }

}