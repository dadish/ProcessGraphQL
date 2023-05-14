<?php

namespace ProcessWire\GraphQL\Test\Debug;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class DbQueryCountFieldTest extends GraphQLTestCase
{
  public static $debug;

  public static function setUpBeforeClass(): void
  {
    self::$debug = Utils::config()->debug;
    parent::setUpBeforeClass();
  }

  public static function tearDownAfterClass(): void
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
    $res = self::execute($query);
    self::assertObjectHasProperty(
      "errors",
      $res,
      "When debug turned off, `dbQueryCount` field must be unavailable."
    );
    assertStringContainsString(
      "dbQuery",
      $res->errors[0]->message,
      "Incorrect error message."
    );
  }

  public function testValue()
  {
    Utils::config()->debug = true;
    $query = '{
      dbQuery
    }';
    $res = self::execute($query);
    self::assertEquals(
      count(\ProcessWire\Database::getQueryLog()),
      count($res->data->dbQuery),
      "`dbQueryCount` must return the number of db query logs"
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
