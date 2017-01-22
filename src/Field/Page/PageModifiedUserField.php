<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\PageType as PageObjectType;
use ProcessWire\NullPage;

class PageModifiedUserField extends AbstractField {

  public function getType()
  {
    return new NonNullType(new PageObjectType());
  }

  public function getName()
  {
    return 'modifiedUser';
  }

  public function getDescription()
  {
    return 'The user that last modified this page.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    return $value->modifiedUser;
  }

}