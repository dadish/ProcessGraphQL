<?php

/**
 * `parent` field selector respects access rules.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParent;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["parent", "name"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parent (s: \"template=cities\") {
            name
          }
        }
      }
    }";
    $res = self::execute($query);
    self::assertTrue(
      is_null($res->data->skyscraper->list[0]->parent),
      "parent returns null if no access."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
