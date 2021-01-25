<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageModified;

/**
 * Returns the correct default value.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["modified"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				modified
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->modified,
      $res->data->skyscraper->list[0]->modified,
      "Retrieves correct default value of `modified` field of the page."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
