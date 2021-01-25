<?php

/**
 * `parents` field selector respects access rules.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParents;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "city"],
    "legalPageFields" => ["parents", "name"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parents (s: \"template=cities\") {
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
      "parents returns empty list if no access."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
