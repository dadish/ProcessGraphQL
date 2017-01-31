<?php

namespace ProcessWire\GraphQL\Field\Page;

use ProcessWire\GraphQL\Field\Page\AbstractPageField;
use ProcessWire\GraphQL\Type\Union\PageUnion;

class PagePrevField extends AbstractPageField {

  public function getType()
  {
    return new PageUnion();
  }

  public function getName()
  {
    return 'prev';
  }

  public function getDescription()
  {
    return "This page's previous sibling page, or null if it is the first sibling.";
  }

}