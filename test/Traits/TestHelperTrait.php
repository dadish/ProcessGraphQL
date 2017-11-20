<?php

trait TestHelperTrait {
  
  public function wire($name = 'wire')
  {
    return \ProcessWire\wire($name);
  }

  public function module()
  {
    return $this->wire('modules')->get('ProcessGraphQL');
  }

}