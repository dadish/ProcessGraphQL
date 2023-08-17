<?php

namespace ProcessWire\GraphQL\Teste\FieldtypePageParentId;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageParentIDTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["parentID"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parentID
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->parentID,
      $res->data->skyscraper->list[0]->parentID,
      "Retrieves `parentID` field of the page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
