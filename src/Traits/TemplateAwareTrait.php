<?php

namespace ProcessWire\GraphQL\Traits;

use ProcessWire\Template;

trait TemplateAwareTrait {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

}