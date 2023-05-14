<?php

/**
 * When user got access to both requested page template
 * and it's child's template. The `child` field returns
 * the child page.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageChild;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city", "skyscraper"],
    "legalPageFields" => ["child"],
  ];

  public function testValue()
  {
    $city = Utils::pages()->get("template=city");
    $query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				child {
            name
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $city->child->name,
      $res->data->city->list[0]->child->name,
      "Retrieves child page."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
