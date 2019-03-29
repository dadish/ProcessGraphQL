<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageHttpUrlField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'httpUrl';
  }

  public function getDescription()
  {
    return 'Same as `url`, except includes protocol (http or https) and hostname.';
  }

}