<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\UserType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Field\Traits\UserResolverTrait;

class PageModifiedUserField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new UserType());
  }

  public function getName()
  {
    return 'modifiedUser';
  }

  public function getDescription()
  {
    return 'The user that last modified this page.';
  }

  use UserResolverTrait;
}