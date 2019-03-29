<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageUrlField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'url';
  }

  public function getDescription()
  {
    return "The page's URL path from the server's document root (may be the same as the `path`)";
  }

}