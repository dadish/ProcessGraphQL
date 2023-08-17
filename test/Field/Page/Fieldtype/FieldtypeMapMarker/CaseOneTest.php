<?php

/**
 * Retreives correct values.
 *
 */

namespace ProcessWire\GraphQL\Test\FieldtypeMapMarker;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["map"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, map.address!=''");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          map {
            lat
            lng
            address
            zoom
          }
        }
      }
    }";
    $res = self::execute($query);
    $expectedMap = $skyscraper->map;
    $actualMap = $res->data->skyscraper->list[0]->map;
    self::assertEquals(
      $expectedMap->lat,
      $actualMap->lat,
      "Retreives correct lat."
    );
    self::assertEquals(
      $expectedMap->lng,
      $actualMap->lng,
      "Retreives correct lng."
    );
    self::assertEquals(
      $expectedMap->address,
      $actualMap->address,
      "Retreives correct address."
    );
    self::assertEquals(
      $expectedMap->zoom,
      $actualMap->zoom,
      "Retreives correct zoom."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
