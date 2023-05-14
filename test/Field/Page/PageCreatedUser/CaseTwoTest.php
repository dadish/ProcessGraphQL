<?php

/**
 * If user do not have access to user template then
 * createdUser returns empty user object.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageCreatedUser;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["createdUser"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				createdUser {
            name
            email
            id
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      "",
      $res->data->skyscraper->list[0]->createdUser->name,
      "`createdUser->name` is empty string when no access."
    );
    self::assertEquals(
      "",
      $res->data->skyscraper->list[0]->createdUser->email,
      "`createdUser->email` is empty string when no access."
    );
    self::assertEquals(
      "",
      $res->data->skyscraper->list[0]->createdUser->id,
      "`createdUser->id` is empty string when no access."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
