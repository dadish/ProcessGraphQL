<?php

namespace ProcessWire\GraphQL\Test\Fields\Auth;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

/**
 * @backupGlobals disabled
 */
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
    $this->assertEquals('admin', $user->name, 'Unable to login via $session->login()');
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
    $res = $this->execute($query);
    $this->assertEquals(200, $res->data->login->statusCode, 'Unable to login via GraphQL');
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
    $res = $this->execute($query);
    $this->assertEquals(401, $res->data->login->statusCode, 'Unable to login via GraphQL');
  }

}