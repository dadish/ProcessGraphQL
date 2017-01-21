<?php

namespace ProcessWire\GraphQL\Field\Pages;

use Youshido\GraphQL\Execution\ResolveInfo;
use ProcessWire\GraphQL\Field\WireArray\WireArrayFindField;

class PagesCountField extends WireArrayFindfield {

  public function getDescription()
  {
    return 'Count and return how many pages will match the given selector.';
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    parent::resolve(\ProcessWire\wire('pages'), $args, $info);
  }

}