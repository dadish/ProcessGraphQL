<?php

namespace ProcessWire\GraphQL;

class Settings {

  public static function module()
  {
    return \Processwire\wire('modules')->get('GraphQL');
  }

}