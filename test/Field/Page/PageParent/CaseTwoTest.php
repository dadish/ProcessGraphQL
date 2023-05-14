<?php

/**
 * When user got access to requested page template but not
 * to the parent's template. The `parent` field returns
 * null.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParent;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalPageFields" => ["parent"],
  ];

  public function testValue()
  {
    $city = Utils::pages()->get("template=city");
    $query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				parent {
            name
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertTrue(
      is_null($res->data->city->list[0]->parent),
      "Returns null when no access to parent page template."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
