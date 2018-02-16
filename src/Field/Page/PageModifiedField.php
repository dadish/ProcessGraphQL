<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Field\Traits\DatetimeResolverTrait;

class PageModifiedField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'modified';
  }

  public function getDescription()
  {
    return "Date of when the page was last modified.";
  }

  use DatetimeResolverTrait;

}