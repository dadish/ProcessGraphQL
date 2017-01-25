<?php

namespace ProcessWire\GraphQL\Traits;

use ProcessWire\TemplatesArray;
use ProcessWire\GraphQL\Settings;

trait TemplatesAwareTrait {

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

}