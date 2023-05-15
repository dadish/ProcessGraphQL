<?php

/**
 * Updates page properly
 *
 */

namespace ProcessWire\GraphQL\Test\FieldtypeMapMarker;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["map"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = 'mutation updatePage($page: SkyscraperUpdateInput!) {
      skyscraper: updateSkyscraper (page: $page) {
        id
        map {
          lat
          lng
          address
          zoom
        }
      }
    }';
    $lat = 0.015432;
    $lng = -0.098562;
    $address = "23576 Broadway, Chicago";
    $zoom = 3;
    $variables = [
      "page" => [
        "id" => $skyscraper->id,
        "map" => [
          "lat" => $lat,
          "lng" => $lng,
          "address" => $address,
          "zoom" => $zoom,
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    $actualMap = Utils::pages()->get($skyscraper->id)->map;
    self::assertEquals(
      $skyscraper->id,
      $res->data->skyscraper->id,
      "Updates the correct page."
    );
    self::assertEquals(
      $lat,
      $actualMap->lat,
      "Updates lat correctly."
    );
    self::assertEquals(
      $lng,
      $actualMap->lng,
      "Updates lng correctly."
    );
    self::assertEquals(
      $address,
      $actualMap->address,
      "Updates address correctly."
    );
    self::assertEquals(
      $zoom,
      $actualMap->zoom,
      "Updates zoom correctly."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
