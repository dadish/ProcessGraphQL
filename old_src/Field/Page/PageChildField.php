<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Field\AbstractField;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use ProcessWire\GraphQL\Field\Traits\OptionalSelectorTrait;

class PageChildField extends AbstractField {

  use OptionalSelectorTrait;

  public function getType()
  {
    return new PageInterfaceType();
  }

  public function getName()
  {
    return 'child';
  }

  public function getDescription()
  {
    $description = 'The first child of this page. ';
    $description .= 'If the `s`(selector) argument is provided then the first matching child (subpage) that matches the given selector. ';
    $description .= 'Returns a Page or null.';
    return $description;
  }

}