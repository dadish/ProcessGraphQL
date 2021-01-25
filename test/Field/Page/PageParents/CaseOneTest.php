<?php

/**
 * When user got access to both requested page template
 * and it's parent's template. The `parents` field returns
 * the parents.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParents;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "city", "cities", "home"],
    "legalPageFields" => ["parents", "name"],
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
      $skyscraper->parents[0]->name,
      $res->data->skyscraper->list[0]->parents->list[0]->name,
      "Retrieves correct parent page at 0."
    );
    self::assertEquals(
      $skyscraper->parents[1]->name,
      $res->data->skyscraper->list[0]->parents->list[1]->name,
      "Retrieves correct parent page at 1."
    );
    self::assertEquals(
      $skyscraper->parents[2]->name,
      $res->data->skyscraper->list[0]->parents->list[2]->name,
      "Retrieves correct parent page at 2."
    );
    self::assertEquals(
      $skyscraper->parents->count,
      $res->data->skyscraper->list[0]->parents->getTotal,
      "Retrieves correct amount of parent pages."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
