<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;

abstract class AbstractFieldtype extends AbstractField {

  protected $name;

  public function __construct(string $name)
  {
    $this->name = $name;
    parent::__construct([]);
  }

  public function getName()
  {
    return $this->name;
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->name;
    return $value->$fieldName;
  }

}