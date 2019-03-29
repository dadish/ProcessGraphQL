<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\NonNullType;

class PageNumChildrenField extends AbstractField {

  const firstArgumentName = 'visible';

  public function getType()
  {
    return new NonNullType(new IntType());
  }

  public function getName()
  {
    return 'numChildren';
  }

  public function getDescription()
  {
    $description = "The number of children (subpages) this page has, optionally limiting to visible pages. ";
    $description .= "When argument `visible` true, number includes only visible children (excludes unpublished, hidden, no-access, etc.)";
    return $description;
  }

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => self::firstArgumentName,
      'type' => new BooleanType(),
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $visible = false;
    if (isset($args[self::firstArgumentName])) {
      $visible = $args[self::firstArgumentName];
    }
    if ($visible) return $value->numChildren($visible);
    return $value->numChildren;
  }

}