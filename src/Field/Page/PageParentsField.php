<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageArrayType as PageArrayObjectType;

class PageParentsField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageArrayObjectType());
  }

  public function getName()
  {
    return 'parents';
  }

  public function getDescription()
  {
    return "Return this page's parent pages as PageArray. Optionally filtered by a selector.";
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
    if (isset($args[$s])) return $value->parents($args[$s]);
    return $value->parents;
  }

}