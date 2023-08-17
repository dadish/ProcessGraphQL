<?php

namespace ProcessWire\GraphQL\Test\FieldtypeHttpUrl;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageHttpUrlTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["httpUrl"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          httpUrl
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->httpUrl,
      $res->data->skyscraper->list[0]->httpUrl,
      "Retrieves `httpUrl` field of the page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
