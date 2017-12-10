<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\DateTimeType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeDatetime extends AbstractFieldtype {

  public static $format = 'Y-m-d H:i:s';

  public function getDefaultType()
  {
    return new DatetimeType();
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->field->name;
    $result = $value->$fieldName;
    if (!$result) return null;
    return date(self::$format, $result);
  }

}
