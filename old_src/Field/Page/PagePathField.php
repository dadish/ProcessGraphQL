<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PagePathField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'path';
  }

  public function getDescription()
  {
    return "The page's URL path from the homepage (i.e. /about/staff/ryan/)";
  }

}