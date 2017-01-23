<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\WireArray\WireArrayCountField;

class PagesCountField extends WireArrayCountfield {

  public function getDescription()
  {
    return 'Count and return how many pages will match the given selector or all pages of the site if no selector given.';
  }

}