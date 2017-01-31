<?php

namespace ProcessWire\GraphQL\Type\Scalar;

use ProcessWire\Template;
use ProcessWire\Selector;
use ProcessWire\Selectors;
use ProcessWire\SelectorEqual;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;

class TemplatedSelectorType extends SelectorType {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
  }

  public function serialize($selectors)
  {
    $selectors = new Selectors($selectors);
    $templateSelector = self::findSelectorByField($selectors, 'template');
    if ($templateSelector instanceof Selector) $selectors->remove($templateSelector);
    $templateSelector = new SelectorEqual('template', $this->template->name);
    $selectors->add($templateSelector);
    return parent::serialize($selectors);
  }

}