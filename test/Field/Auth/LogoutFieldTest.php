<?php

namespace ProcessWire\GraphQL\Test\Fields\Auth;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class LogoutFieldTest extends GraphQLTestCase {

  public function tearDown()
  {
    Utils::session()->logout();
  }

  public function testLogout()
  {
    $config = Utils::config();
    Utils::session()->login('admin', $config->testUsers['admin']);
    $user = Utils::user();
    assertTrue($user->isSuperuser());

    $query = '{
      logout {
        statusCode
      }
    }';
    $res = self::execute($query);
    assertEquals(200, $res->data->logout->statusCode, 'Unable to logout via GraphQL');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

  public function testLogoutFailure()
  {
    $query = '{
      logout {
        statusCode
      }
    }';
    $res = self::execute($query);
    assertObjectHasAttribute('errors', $res, 'Unable to logout via GraphQL');
  }

}