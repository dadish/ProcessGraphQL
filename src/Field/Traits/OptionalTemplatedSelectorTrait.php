<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use ProcessWire\GraphQL\Type\Scalar\TemplatedSelectorType;

trait OptionalTemplatedSelectorTrait {

  protected $defaultSelector;

  public function build(FieldConfig $config)
  {
    $defaultSelector = new TemplatedSelectorType($this->template);
    $this->defaultSelector = $defaultSelector->serialize("");
    $config->addArgument(new InputField([
      'name' => TemplatedSelectorType::ARGUMENT_NAME,
      'type' => new TemplatedSelectorType($this->template),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    if (isset($args[TemplatedSelectorType::ARGUMENT_NAME])) {
      $selector = $args[TemplatedSelectorType::ARGUMENT_NAME];
    } else {
      $selector = $this->defaultSelector;
    }
    $fieldName = $this->getName();
    $result = $value->$fieldName($selector);
    if ($result instanceof NullPage) return null;
    return $return;
  }

}