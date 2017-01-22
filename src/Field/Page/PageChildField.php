<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PageChildField extends AbstractField {

  public function getType()
  {
    return new PageObjectType();
  }

  public function getName()
  {
    return 'child';
  }

  public function getDescription()
  {
    $description = 'The first child of this page. ';
    $description .= 'If the `s`(selector) argument is provided then the first matching child (subpage) that matches the given selector. ';
    $description .= 'Returns a Page or null.';
    return $description;
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
    if (isset($args[$s])) $child = $value->child($args[$s]);
    else $child = $value->child;
    if ($child instanceof NullPage) return null;
    return $child;
  }

}