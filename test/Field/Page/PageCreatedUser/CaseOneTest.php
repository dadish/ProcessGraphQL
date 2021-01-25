<?php

/**
 * If user has access to user template then
 * createdUser returns a user Page.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageCreatedUser;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "user"],
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
          }
  			}
  		}
  	}";
    $res = $this->execute($query);
    self::assertEquals(
      $skyscraper->createdUser->name,
      $res->data->skyscraper->list[0]->createdUser->name,
      "Retrieves `createdUser` field of the page."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
