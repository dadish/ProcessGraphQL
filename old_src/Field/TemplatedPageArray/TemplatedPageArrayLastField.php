<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\Template;
use ProcessWire\GraphQL\Field\PageArray\PageArrayLastField;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;

class TemplatedPageArrayLastField extends PageArrayLastField {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public function getType()
  {
    return new TemplatedPageType($this->template);
  }

}