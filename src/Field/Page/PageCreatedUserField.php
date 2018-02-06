<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Type\Object\UserType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Utils;
use ProcessWire\WireData;
use ProcessWire\Page;

class PageCreatedUserField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new UserType());
  }

  public function getName()
  {
    return 'createdUser';
  }

  public function getDescription()
  {
    return 'The user that created this page.';
  }

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