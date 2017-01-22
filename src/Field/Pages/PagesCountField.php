<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;

class PagesCountField extends WireArrayFindfield {

  public function getDescription()
  {
    return 'Count and return how many pages will match the given selector or all pages if no selector given.';
  }

}