<?php

namespace ProcessWire\GraphQL\Type\Union;

use Youshido\GraphQL\Type\Union\AbstractUnionType;
use ProcessWire\TemplatesArray;
use ProcessWire\NullPage;
use ProcessWire\GraphQL\Settings;
use ProcessWire\GraphQL\Type\Object\TemplatedPageType;
use ProcessWire\GraphQL\Type\Object\NullPageType;

class PageUnion extends AbstractUnionType {

  protected $templates;

  public function __construct(TemplatesArray $templates = null)
  {
    $this->templates = $templates;
    parent::__construct([]);
  }

  public function getTemplates()
  {
    if ($this->templates instanceof TemplatesArray) return $this->templates;
    return Settings::getLegalTemplates();
  }
  
  public function getTypes()
  {
    $types = [];
    foreach ($this->getTemplates() as $template) {
      $types[] = new TemplatedPageType($template);
    }
    $types[] = new NullPageType();
    return $types;
  }

  public function resolveType($page)
  {
    if (is_null($page) || $page instanceof NullPage) return new NullPageType();
    return new TemplatedPageType($page->template);
  }

}