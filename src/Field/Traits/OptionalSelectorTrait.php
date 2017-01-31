<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\NullPage;

trait OptionalSelectorTrait {

  public function build(FieldConfig $config)
  {
    $defaultSelector = new SelectorType();
    $config->addArgument(new InputField([
      'name' => SelectorType::ARGUMENT_NAME,
      'type' => new SelectorType(),
      'default' => $defaultSelector->serialize(""),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $selector = $args[SelectorType::ARGUMENT_NAME];
    $fieldName = $this->getName();
    $result = $value->$fieldName($selector);
    if ($result instanceof NullPage) return null;
    return $result;
  }

}