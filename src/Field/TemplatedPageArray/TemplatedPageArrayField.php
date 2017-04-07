<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\Template;
use Processwire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\Object\TemplatedPageArrayType;
use ProcessWire\GraphQL\Type\Scalar\TemplatedSelectorType;
use ProcessWire\GraphQL\Field\Traits\OptionalTemplatedSelectorTrait;

class TemplatedPageArrayField extends AbstractField {

  use OptionalTemplatedSelectorTrait;

  protected $template;

  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public function getType()
  {
    return new TemplatedPageArrayType($this->template);
  }

  public function getName()
  {
    return TemplatedPageArrayType::normalizeName($this->template->name);
  }

  public function getDescription()
  {
    $desc = $this->template->description;
    if ($desc) return $desc;
    return "PageArray that stores only pages with template `" . $this->template->name . "`.";
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    if (isset($args[TemplatedSelectorType::ARGUMENT_NAME])) {
      $selector = $args[TemplatedSelectorType::ARGUMENT_NAME];
    } else {
      $selector = $this->defaultSelector;
    }
    Utils::moduleConfig()->currentTemplateContext = $this->template;
    $pages = \Processwire\wire('pages');
    return $pages->find($selector);
  }

}