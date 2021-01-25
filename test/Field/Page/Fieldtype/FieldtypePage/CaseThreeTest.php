<?php

namespace ProcessWire\GraphQL\Test\FieldtypePage;

/**
 * Test the case when FieldtypePage:derefAsPageOrFalse is set
 * and the value is empty.
 */

use ProcessWire\FieldtypePage;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "architect"],
    "legalFields" => ["architects"],
    "access" => [
      "fields" => [
        [
          "name" => "architects",
          "derefAsPage" => \ProcessWire\FieldtypePage::derefAsPageOrFalse,
        ],
      ],
    ],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get(
      "template=skyscraper, architects.count=0"
    );

    // wake up the architects value
    // which will set it to false
    $skyscraper->architects;

    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				architects {
  					list {
  						id
  						name
  					}
  				}
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      0,
      count($res->data->skyscraper->list[0]->architects->list),
      "Returns empty architect page array."
    );

    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
