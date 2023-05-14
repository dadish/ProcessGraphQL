<?php

namespace ProcessWire\GraphQL\Test\FieldtypeDatetime;

/**
 * It returns correct output when custom dateOutputFormat
 * is set.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["architect"],
    "legalFields" => ["born"],
  ];

  public function testValue()
  {
    // set output format for born (Datetime) field
    Utils::fields()->get("born")->dateOutputFormat = "j F Y H:i:s";

    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born
        }
      }
    }";
    $res = self::execute($query);

    self::assertTrue(
      $architect->outputFormatting(),
      "Output formatting is on."
    );
    self::assertEquals(
      $architect->born,
      $res->data->architect->list[0]->born,
      "Retrieves correctly formatted datetime value."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
