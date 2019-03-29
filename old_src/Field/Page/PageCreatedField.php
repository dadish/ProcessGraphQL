<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Field\Traits\DatetimeResolverTrait;

class PageCreatedField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new StringType());
  }

  public function getName()
  {
    return 'created';
  }

  public function getDescription()
  {
    return "Date of when the page was created.";
  }

  use DatetimeResolverTrait;

}