<?php

namespace ProcessWire\GraphQL\Field\Page;

use Youshido\GraphQL\Type\NonNullType;
use ProcessWire\GraphQL\Type\InterfaceType\PageInterfaceType;
use ProcessWire\GraphQL\Field\Page\AbstractPageField;

class PageRootParentField extends AbstractPageField {

  public function getType()
  {
    return new NonNullType(new PageInterfaceType());
  }

  public function getName()
  {
    return 'rootParent';
  }

  public function getDescription()
  {
    return 'The parent page closest to the homepage (typically used for identifying a section)';
  }

}