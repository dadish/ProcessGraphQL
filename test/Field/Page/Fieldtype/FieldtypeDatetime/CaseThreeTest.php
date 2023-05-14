<?php

namespace ProcessWire\GraphQL\Test\FieldtypeDatetime;

/**
 * It accepts format argument and correctly preformats it for the output.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["architect"],
    "legalFields" => ["born"],
  ];

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $format = "j/F/Y H-i-s";
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born (format: \"$format\")
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      date($format, $architect->getUnformatted("born")),
      $res->data->architect->list[0]->born,
      "Formats datetime value correctly via format argument."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
