<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\Field;

abstract class AbstractFieldtype extends AbstractField {

  protected $field;

  public function __construct(Field $field)
  {
    $this->field = $field;
    parent::__construct([]);
  }

  public function getType()
  {
    if ($this->field->required) return new NonNullType($this->getDefaultType());
    return $this->getDefaultType();
  }

  public function getName()
  {
    return $this->field->name;
  }

  public function getDescription()
  {
    return $this->field->description;
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    return $value->$fieldName;
  }

}