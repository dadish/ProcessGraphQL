<?php

namespace ProcessWire\GraphQL\Test\FieldtypeDatetime;

/**
 * It returns correct default output.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["architect"],
    "legalFields" => ["born"],
  ];
  const FIELD_NAME = "born";
  const FIELD_TYPE = "FieldtypeDatetime";

  use FieldtypeTestTrait;

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $architect->born,
      $res->data->architect->list[0]->born,
      "Retrieves datetime value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
