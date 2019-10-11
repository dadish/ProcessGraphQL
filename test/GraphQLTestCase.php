<?php

namespace ProcessWire\GraphQL\Test;

use PHPUnit\Framework\TestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Schema;

abstract class GraphqlTestCase extends TestCase {

  public static $defaultConfig;

  public static function setUpBeforeClass()
  {
    $self = static::class;
    if (method_exists($self, 'setupAccess')) {
      $self::setupAccess();
    }
    self::$defaultConfig = Utils::module()->data();
  }

  public static function tearDownAfterClass()
  {
    Utils::module()->setArray(self::$defaultConfig);
    $self = static::class;
    if (method_exists($self, 'teardownAccess')) {
      $self::teardownAccess();
    }
  }

  public static function execute($payload = null, $variables = null)
  {
    Schema::build();
  	return Utils::module()->executeGraphQL($payload, $variables);
  }

}