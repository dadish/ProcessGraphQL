<?php

/**
 * If user do not have access to user template then
 * modifiedUser returns empty user object.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageModifiedUser;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["modifiedUser"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				modifiedUser {
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
      $res->data->skyscraper->list[0]->modifiedUser->name,
      "`modifiedUser->name` is empty string when no access."
    );
    self::assertEquals(
      "",
      $res->data->skyscraper->list[0]->modifiedUser->email,
      "`modifiedUser->email` is empty string when no access."
    );
    self::assertEquals(
      "",
      $res->data->skyscraper->list[0]->modifiedUser->id,
      "`modifiedUser->id` is empty string when no access."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
