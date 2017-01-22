<?php

namespace ProcessWire\GraphQL\Field\WireArray;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\WireArrayType;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;

class WireArrayFindField extends AbstractField {

  public function getType()
  {
    return new WireArrayType();
  }

  public function getName()
  {
    return 'find';
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => SelectorType::ARGUMENT_NAME,
      'type' => new NonNullType(new SelectorType()),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $selector = $args[SelectorType::ARGUMENT_NAME];
    return $value->find($selector);
  }

}