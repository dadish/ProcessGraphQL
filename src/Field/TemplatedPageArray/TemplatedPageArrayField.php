<?php

namespace ProcessWire\GraphQL\Field\TemplatedPageArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Field\InputField;
use ProcessWire\GraphQL\Traits\TemplateAwareTrait;
use ProcessWire\GraphQL\Type\Scalar\TemplatedSelectorType;
use ProcessWire\GraphQL\Type\Object\TemplatedPageArrayType;

class TemplatedPageArrayField extends AbstractField {

  use TemplateAwareTrait;

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

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => TemplatedSelectorType::ARGUMENT_NAME,
      'type' => new TemplatedSelectorType($this->template),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $pages = \Processwire\wire('pages');
    $s = TemplatedSelectorType::ARGUMENT_NAME;
    if (isset($args[$s])) return $pages->find($args[$s]);
    
    $selector = new TemplatedSelectorType($this->template);
    return $pages->find($selector->serialize(""));
  }

}