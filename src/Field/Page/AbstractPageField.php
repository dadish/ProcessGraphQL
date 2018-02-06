<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\NullPage;

abstract class AbstractPageField extends AbstractField {

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->getName();
    $result = $value->$fieldName;
    if ($result instanceof NullPage) {
      return null;
    }
    return $result;
  }

}