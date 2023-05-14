<?php

namespace ProcessWire\GraphQL\Test\FieldtypePagePath;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PagePathTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["path"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          path
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->path,
      $res->data->skyscraper->list[0]->path,
      "Retrieves `path` field of the page."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
