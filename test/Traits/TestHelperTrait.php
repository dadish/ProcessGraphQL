<?php

trait TestHelperTrait {

  public static $defaultConfig;
  
  public function wire($name = 'wire')
  {
    return \ProcessWire\wire($name);
  }

  public function module()
  {
    return $this->wire('modules')->get('ProcessGraphQL');
  }

  public static function setUpBeforeClass()
  {
    self::$defaultConfig = \ProcessWire\wire('modules')->get('ProcessGraphQL')->data();
  }

  public static function tearDownAfterClass()
  {
    \ProcessWire\wire('modules')->get('ProcessGraphQL')->setArray(self::$defaultConfig);
  }

}