<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use ProcessWire\GraphQL\Type\Scalar\TemplatedSelectorType;

trait OptionalTemplatedSelectorTrait {

  public function build(FieldConfig $config)
  {
    $defaultValue = new TemplatedSelectorType($this->template);
    $config->addArgument(new InputField([
      'name' => TemplatedSelectorType::ARGUMENT_NAME,
      'type' => new TemplatedSelectorType($this->template),
      'default' => $defaultValue->serialize(""),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $selector = $args[TemplatedSelectorType::ARGUMENT_NAME];
    $fieldName = $this->getName();
    $result = $value->$fieldName($selector);
    if ($result instanceof NullPage) return null;
    return $return;
  }

}