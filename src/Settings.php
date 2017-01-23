<?php

namespace ProcessWire\GraphQL;

class Settings {

  public function module()
  {
    return \Processwire\wire('modules')->get('GraphQL');
  }

}