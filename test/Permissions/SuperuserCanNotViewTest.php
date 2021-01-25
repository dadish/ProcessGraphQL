<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCanNotViewTest extends GraphqlTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
  ];

  public function testSuperuserCanNotView()
  {
    $target = Utils::pages()->get("template=architect, sort=random");
    $query = "{
      architect(s: \"id={$target->id}\") {
        list {
          id
          name
          url
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(1, count($res->errors));
    assertStringContainsString("architect", $res->errors[0]->message);
  }
}
