<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\NullPage;

trait OptionalSelectorTrait {

  protected $defaultSelector;

  public function build(FieldConfig $config)
  {
    $defaultSelector = new SelectorType();
    $this->defaultSelector = $defaultSelector->serialize("");
    $config->addArgument(new InputField([
      'name' => SelectorType::ARGUMENT_NAME,
      'type' => new SelectorType(),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    if (isset($args[SelectorType::ARGUMENT_NAME])) {
      $selector = $args[SelectorType::ARGUMENT_NAME];
    } else {
      $selector = $this->defaultSelector;
    }
    $fieldName = $this->getName();
    $result = $value->$fieldName($selector);
    if ($result instanceof NullPage) return null;
    return $result;
  }

}