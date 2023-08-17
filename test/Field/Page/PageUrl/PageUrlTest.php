<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageUrl;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageUrlTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["url"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          url
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->url,
      $res->data->skyscraper->list[0]->url,
      "Retrieves `url` field of the page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
