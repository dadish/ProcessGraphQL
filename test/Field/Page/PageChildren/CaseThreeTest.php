<?php

/**
 * `children` field supports optional selectors.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageChildren;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["home", "cities", "architects", "search", "list-all"],
    "legalPageFields" => ["children"],
  ];

  public function testValue()
  {
    $home = Utils::pages()->get("template=home");
    $query = "{
      home (s: \"id=$home->id\") {
        list {
          children (s: \"template=cities|architects\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $children = $home->children("template=cities|architects");
    self::assertEquals(
      $children->count,
      count($res->data->home->list[0]->children->list),
      "Returns the correct number of pages."
    );
    self::assertEquals(
      $children[0]->name,
      $res->data->home->list[0]->children->list[0]->name,
      "Returns the correct page at 0."
    );
    self::assertEquals(
      $children[1]->name,
      $res->data->home->list[0]->children->list[1]->name,
      "Returns the correct page at 0."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
