<?php

namespace ProcessWire\GraphQL\Test\Fields\Auth;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class LogoutFieldTest extends GraphQLTestCase
{
  public function tearDown(): void
  {
    Utils::session()->logout();
  }

  public function testLogout()
  {
    $config = Utils::config();
    Utils::session()->login("admin", $config->testUsers["admin"]);
    $user = Utils::user();
    self::assertTrue($user->isSuperuser());

    $query = '{
      logout {
        statusCode
      }
    }';
    $res = self::execute($query);
    self::assertEquals(
      200,
      $res->data->logout->statusCode,
      "Unable to logout via GraphQL"
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }

  public function testLogoutFailure()
  {
    $query = '{
      logout {
        statusCode
      }
    }';
    $res = self::execute($query);
    self::assertObjectHasPropertyOrAttribute(
      "errors",
      $res,
      "Unable to logout via GraphQL"
    );
  }
}
