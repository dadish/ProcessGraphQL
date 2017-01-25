<?php

namespace ProcessWire\GraphQL\Type\Union;

use Youshido\GraphQL\Type\Union\AbstractUnionType;
use ProcessWire\GraphQL\Traits\TemplatesAwareTrait;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;

class PageUnion extends AbstractUnionType {

  use TemplatesAwareTrait;
  
  public function getTypes()
  {
    $types = [];
    foreach ($this->getTemplates() as $template) {
      $types[] = new TemplatedPageType($template);
    }
    return $types;
  }

  public function resolveType($page)
  {
    return new TemplatedPageType($page->template);
  }

}