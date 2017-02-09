<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\ListType\ListType;
use ProcessWire\FieldtypeFile as PWFieldtypeFile;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Object\PageFileType;

class FieldtypeFile extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new ListType(new PageFileType());
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