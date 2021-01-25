<?php

/**
 * `parents` field supports optional selector.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParents;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "cities", "home"],
    "legalPageFields" => ["parents", "name"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parents (s: \"template=cities|home\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $parents = $skyscraper->parents("template=cities|home");
    self::assertEquals(
      $parents[0]->name,
      $res->data->skyscraper->list[0]->parents->list[0]->name,
      "Retrieves correct parent page at 0."
    );
    self::assertEquals(
      $parents[1]->name,
      $res->data->skyscraper->list[0]->parents->list[1]->name,
      "Retrieves correct parent page at 1."
    );
    self::assertEquals(
      $parents->count,
      count($res->data->skyscraper->list[0]->parents->list),
      "Retrieves correct amount of parent pages."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
