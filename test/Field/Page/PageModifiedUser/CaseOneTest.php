<?php

/**
 * If user has access to user template then
 * modifiedUser returns a user Page.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageModifiedUser;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "user"],
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
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->modifiedUser->name,
      $res->data->skyscraper->list[0]->modifiedUser->name,
      "Retrieves `modifiedUser` field of the page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
