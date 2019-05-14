<?php

namespace ProcessWire\GraphQL\Test\Debug;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class DbQueryCountFieldTest extends GraphQLTestCase {

  public static $debug;

  public static function setUpBeforeClass()
  {
    self::$debug = Utils::config()->debug;
    parent::setUpBeforeClass();
  }

  public static function tearDownAfterClass()
  {
    Utils::config()->debug = self::$debug;
    parent::tearDownAfterClass();
  }

  public function testUnavailable()
  {
    Utils::config()->debug = false;
    $query = '{
      dbQuery
    }';
    $res = $this->execute($query);
    $this->assertObjectHasAttribute('errors', $res, 'When debug turned off, `dbQueryCount` field must be unavailable.');
  }

  public function testValue()
  {
    Utils::config()->debug = true;
    $query = '{
      dbQuery
    }';
    $res = $this->execute($query);
    $this->assertEquals(count(\ProcessWire\Database::getQueryLog()), count($res->data->dbQuery), '`dbQueryCount` must return the number of db query logs');
  }

}