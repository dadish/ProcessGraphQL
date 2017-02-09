<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\UserType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

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

}