<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageNumChildren;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageNumChildrenTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["numChildren"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          numChildren
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->numChildren,
      $res->data->skyscraper->list[0]->numChildren,
      "Retrieves `numChildren` field of the page."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
