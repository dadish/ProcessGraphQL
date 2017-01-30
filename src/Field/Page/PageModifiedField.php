<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageModifiedField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new IntType());
  }

  public function getName()
  {
    return 'modified';
  }

  public function getDescription()
  {
    return "Unix timestamp of when the page was last modified.";
  }

}