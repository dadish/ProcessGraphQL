<?php

namespace ProcessWire;

use ProcessWire\Page;

class BasicPagePage extends Page
{
  public function get($key)
  {
    return $this->customGet($key);
  }

  public function ___customGet($key)
  {
    return parent::get($key);
  }
}
