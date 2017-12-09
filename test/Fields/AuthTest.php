<?php

namespace ProcessWire\GraphQL\Test\Fields;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

/**
 * @backupGlobals disabled
 */
class AuthTest extends GraphQLTestCase {

  public function testLoginCredentials()
  {
    Utils::session()->login('admin', 'skyscrapers-admin');
    $user = Utils::user();
    $this->assertEquals('admin', $user->name, 'Unable to login via $session->login()');
    Utils::session()->logout();
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
    $response = Utils::module()->executeGraphQL($query);
    $respObj = json_decode($response);
    $this->assertEquals(200, $respObj->data->login->statusCode, 'Unable to login via GraphQL');
    Utils::session()->logout();
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
    $response = Utils::module()->executeGraphQL($query);
    $respObj = json_decode($response);
    $this->assertEquals(401, $respObj->data->login->statusCode, 'Unable to login via GraphQL');
    Utils::session()->logout();
  }

}