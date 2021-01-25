<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageCreated;

/**
 * Returns correct default value.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["created"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          created
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->created,
      $res->data->skyscraper->list[0]->created,
      "Retrieves correct default `created` value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
