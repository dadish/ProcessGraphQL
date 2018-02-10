<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\NullPage;
use ProcessWire\GraphQL\Utils;
use ProcessWire\WireData;
use ProcessWire\Page;

trait PageResolverTrait {

  public function emptyValue()
  {
    $value = null;
    $type = $this->getType();
    if ($type instanceof NonNullType) {      
      $value = new WireData();
      $value->name = '';
      $value->id = '';
    }
    return $value;
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->getName();
    $emptyValue = $this->emptyValue();
    $result = $value->$fieldName;

    // if result is null then set result to empty value
    if (is_null($result)) {
      $result = $emptyValue;
    }

    // if it is a NullPage then set result to empty value
    if ($result instanceof NullPage) {
      $result = $emptyValue;
    }

    // if it is a Page then check if it is legal
    if ($result instanceof Page) {
      $templateName = $result->template->name;

      // if it is illegal then set result to empty value
      if (!Utils::moduleConfig()->legalViewTemplates->find("name=$templateName")->count()) {
        $result = $emptyValue;
      }
    }

    // return the result
    return $result;
  }

}