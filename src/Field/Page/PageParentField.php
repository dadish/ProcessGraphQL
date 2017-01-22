<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PageParentField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'parent';
  }

  public function getDescription()
  {
    return 'The parent Page object, or the closest parent matching the given selector. Returns `null` if there is no parent or no match.';
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
    if (isset($args[$s])) $parent = $value->parent($args[$s]);
    else $parent = $value->parent;
    if ($parent instanceof NullPage) return null;
    return $parent;
  }

}