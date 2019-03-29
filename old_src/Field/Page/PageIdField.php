<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageIdField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new IdType());
  }

  public function getName()
  {
    return 'id';
  }

  public function getDescription()
  {
    return 'The numbered ID of the page.';
  }

}