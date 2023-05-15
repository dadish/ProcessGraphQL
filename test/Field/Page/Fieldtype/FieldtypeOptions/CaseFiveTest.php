<?php

/**
 * Empty options field should return null.
 */

namespace ProcessWire\GraphQL\Test\FieldtypeOptions;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFiveTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["options_single"],
  ];

  public function testValue()
  {
    $city = Utils::pages()->get("template=city, options_single=''");
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
    self::assertNull($res->data->city->list[0]->options_single, "Empty option field should return null.");
  }
}
