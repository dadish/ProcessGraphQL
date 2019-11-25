<?php

namespace ProcessWire\GraphQL\Test\Fields\Auth;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class LoginFieldTest extends GraphQLTestCase {

  public function tearDown()
  {
    Utils::session()->logout();
  }

  public function testLoginCredentials()
  {
    $config = Utils::config();
    Utils::session()->login('admin', $config->testUsers['admin']);
    $user = Utils::user();
    assertEquals('admin', $user->name, 'Unable to login via $session->login()');
  }

  public function testLoginSuccess()
  {
    $config = Utils::config();
    $pass = $config->testUsers['admin'];
    $query = "{
      login(name:\"admin\", pass:\"$pass\") {
        statusCode
        message
      }
    }";
    $res = self::execute($query);
    assertEquals(200, $res->data->login->statusCode, 'Unable to login via GraphQL');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

  public function testLoginFailure()
  {
    $config = Utils::config();
    $pass = 'some-random-stuff';
    $query = "{
      login(name:\"admin\", pass:\"$pass\") {
        statusCode
        message
      }
    }";
    $res = self::execute($query);
    assertEquals(401, $res->data->login->statusCode, 'Unable to login via GraphQL');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}