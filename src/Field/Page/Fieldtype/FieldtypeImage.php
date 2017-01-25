<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\PageImageType;

class FieldtypeImage extends AbstractFieldtype {

  public function getType()
  {
    return new ListType(new PageImageType());
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    // first we turn of output formatting because we handle
    // FeildtypeImage on as a PageImages/WireArray
    $value->of(false);

    // get the desired value
    $fieldName = $this->field->name;
    $images = $value->$fieldName;

    // turn the output formatting back on so that other fieldtypes
    // are handled properly
    $value->of(true);

    // return the value
    return $images;
  }

}