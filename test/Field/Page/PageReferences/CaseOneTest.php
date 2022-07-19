<?php

/**
 * When both requested page template and it's referenses' templates
 * are legal templates then admin user gets all the pages.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageReferences;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "architect"],
    "legalPageFields" => ["references", "name"],
    "legalFields" => ["architects"]
  ];

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect (s: \"id=$architect->id\") {
        list {
          name
          references {
            getTotal
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $architect->references()[0]->name,
      $res->data->architect->list[0]->references->list[0]->name,
      "Retrieves correct reference page at 0."
    );
    self::assertEquals(
      $architect->references()[1]->name,
      $res->data->architect->list[0]->references->list[1]->name,
      "Retrieves correct reference page at 1."
    );
    self::assertEquals(
      $architect->references()[2]->name,
      $res->data->architect->list[0]->references->list[2]->name,
      "Retrieves correct reference page at 2."
    );
    self::assertEquals(
      $architect->references->count,
      $res->data->architect->list[0]->references->getTotal,
      "Retrieves correct amount of reference pages."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
