<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;

class PageSiblingsField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'siblings';
  }

  public function getDescription()
  {
    return "All the sibling pages of this page. Optionally filter them with selector. Returns a PageArray.";
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => SelectorType::ARGUMENT_NAME,
      'type' => new SelectorType(),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $s = SelectorType::ARGUMENT_NAME;
    if (isset($args[$s])) return $value->siblings($args[$s]);
    return $value->siblings;
  }

}