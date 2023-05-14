<?php

namespace ProcessWire\GraphQL\Test\FieldtypeInteger;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class CaseTwoTest extends GraphqlTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["floors"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get(
      "template=skyscraper, sort=random, floors=0"
    );
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          floors
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      0,
      $res->data->skyscraper->list[0]->floors,
      "Retrieves incorrect value."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
