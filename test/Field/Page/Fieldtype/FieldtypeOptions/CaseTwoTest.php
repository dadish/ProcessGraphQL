<?php

namespace ProcessWire\GraphQL\Test\FieldtypeOptions;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["options_single"],
  ];

  public function testValue()
  {
    $city = Utils::pages()->get("template=city, options_single.count>0");
    $query = "{
      city (s: \"id=$city->id\") {
        list {
          options_single {
            title
            value
            id
          }
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $city->options_single->title,
      $res->data->city->list[0]->options_single->title,
      "Retrieves correct option title."
    );
    self::assertEquals(
      $city->options_single->value,
      $res->data->city->list[0]->options_single->value,
      "Retrieves correct option value."
    );
    self::assertEquals(
      $city->options_single->id,
      $res->data->city->list[0]->options_single->id,
      "Retrieves correct option id."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
