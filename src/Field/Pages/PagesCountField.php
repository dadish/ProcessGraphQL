<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\IntType;

use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use \ChromePhp;

class PagesCountField extends AbstractField {

  public function getType()
  {
    return new IntType();
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => SelectorType::ARGUMENT_NAME,
      'type' => new SelectorType(),
    ]));
  }

  public function getName()
  {
    return 'count';
  }

  public function getDescription()
  {
    return 'Count and return how many pages will match the given selector.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $s = SelectorType::ARGUMENT_NAME;
    if (isset($args[$s])) return \ProcessWire\wire('pages')->count($args[$s]);
    return \ProcessWire\wire('pages')->count();
  }

}