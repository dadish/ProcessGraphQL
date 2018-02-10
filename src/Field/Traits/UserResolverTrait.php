<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\NullPage;
use ProcessWire\GraphQL\Utils;
use ProcessWire\WireData;
use ProcessWire\Page;

trait UserResolverTrait {

  public function emptyValue()
  {
    $value = new WireData();
    $value->name = '';
    $value->email = '';
    $value->id = '';
    return $value;
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->getName();
    $result = $value->$fieldName;
    if ($result instanceof NullPage) {
      return $this->emptyValue();
    }
    if ($result instanceof Page) {
      $templateName = $result->template->name;
      if (Utils::moduleConfig()->legalViewTemplates->find("name=$templateName")->count()) {
        return $result;
      } else {
        return $this->emptyValue();
      }
    }
    return $result;
  }

}