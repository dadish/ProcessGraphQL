<?php

/**
 * `parent` field supports optional selector.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParent;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["cities", "skyscraper"],
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
    self::assertEquals(
      $skyscraper->parent("template=cities")->name,
      $res->data->skyscraper->list[0]->parent->name,
      "Retrieves parent page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
