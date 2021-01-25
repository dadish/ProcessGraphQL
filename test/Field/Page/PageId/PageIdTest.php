<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageId;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageIdTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["id"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          id
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->id,
      $res->data->skyscraper->list[0]->id,
      "Retrieves `id` field of the page."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
