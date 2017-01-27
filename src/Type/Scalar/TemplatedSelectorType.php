<?php

namespace ProcessWire\GraphQL\Type\Scalar;

use ProcessWire\Template;
use ProcessWire\Selector;
use ProcessWire\SelectorEqual;
use ProcessWire\GraphQL\Type\Scalar;

class TemplatedSelectorType extends SelectorType {

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
  }

  public function serialize($selectors)
  {
    $selectors = parent::serialize($selectors);
    $templateSelector = self::findSelectorByField($selectors, 'template');
    if ($templateSelector instanceof Selector) $selectors->remove($templateSelector);
    $templateSelector = new SelectorEqual('template', $this->template->name);
    $selectors->add($templateSelector);
    return $selectors;
  }

}