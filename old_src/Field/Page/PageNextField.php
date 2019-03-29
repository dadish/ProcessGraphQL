<?php

namespace ProcessWire\GraphQL\Field\Page;

use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use ProcessWire\GraphQL\Field\Traits\PageResolverTrait;

class PageNextField extends AbstractPageField {

  public function getType()
  {
    return new PageInterfaceType();
  }

  public function getName()
  {
    return 'next';
  }

  public function getDescription()
  {
    return "This page's next sibling page, or null if it is the last sibling.";
  }

  use PageResolverTrait;

}