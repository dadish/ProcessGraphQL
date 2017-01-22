<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;

class PageFindField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'find';
  }

  public function getDescription()
  {
    return "Find pages matching the selector anywhere below this page (children, grandchildren, etc.). Returns a PageArray.";
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