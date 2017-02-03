<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\FieldtypeFile as PWFieldtypeFile;

class FieldtypeFile extends AbstractFieldtype {

  public function getType()
  {
    return new StringType();
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    
    // we set the outputFormat for fieldtypeImage as
    // FieldtypeFile::outputFormatArray, because we always
    // handle file/image fields as arrays
    $field = \ProcessWire\wire('fields')->get($fieldName);
    $field->outputFormat = PWFieldtypeFile::outputFormatArray;

    return $value->$fieldName;
  }

}