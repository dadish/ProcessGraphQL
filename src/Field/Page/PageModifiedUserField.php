<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\Object\UserType;
use ProcessWire\NullPage;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

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

}