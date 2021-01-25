<?php

namespace ProcessWire\GraphQL\Teste\FieldtypeInteger;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class CaseThreeTest extends GraphqlTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["floors"],
    "access" => [
      "fields" => [
        [
          "name" => "floors",
          "zeroNotEmpty" => true,
        ],
      ],
    ],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get(
      "template=skyscraper, sort=random, floors=''"
    );
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          floors
        }
      }
    }";
    $res = self::execute($query);
    self::assertNull(
      $res->data->skyscraper->list[0]->floors,
      "Retrieves incorrect value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
