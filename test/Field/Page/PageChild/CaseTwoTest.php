<?php

/**
 * When user got access to requested page template but not
 * to the child's template. The `child` field returns
 * null.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageChild;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
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
    self::assertTrue(
      is_null($res->data->city->list[0]->child),
      "Returns null when no access to child page template."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
