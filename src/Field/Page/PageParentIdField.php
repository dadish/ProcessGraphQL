<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageParentIdField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new IdType());
  }

  public function getName()
  {
    return 'parentID';
  }

  public function getDescription()
  {
    return 'The numbered ID of the parent page or 0 if none.';
  }

}