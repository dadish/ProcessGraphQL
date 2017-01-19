<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;

use ProcessWire\GraphQL\Type\Object\PagesType;

class PagesField extends AbstractField {

  protected $type;

  public function __construct(array $config = [])
  {
    $this->type = new PagesType();
    parent::__construct($config);
  }

  public function getType()
  {
    return $this->type;
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $this->type->resolve($value, $args, $info);
  }
}