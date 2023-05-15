<?php

/**
 * When user got access to requested page template but not
 * to the parents' template. The `parents` field returns
 * empty list.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParents;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["parents"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				parents {
            getTotal
            list {
              name
            }
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      0,
      count($res->data->skyscraper->list[0]->parents->list),
      "Returns empty list when no access to parent pages template."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
