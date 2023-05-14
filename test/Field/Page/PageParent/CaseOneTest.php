<?php

/**
 * When user got access to both requested page template
 * and it's parent's template. The `parent` field returns
 * the parent page.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageParent;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city", "skyscraper"],
    "legalPageFields" => ["parent", "name"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parent {
            name
          }
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->parent->name,
      $res->data->skyscraper->list[0]->parent->name,
      "Retrieves parent page."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
