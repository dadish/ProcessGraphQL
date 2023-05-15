<?php

/**
 * Creates page properly
 *
 */

namespace ProcessWire\GraphQL\Test\FieldtypeMapMarker;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use ProcessWire\GraphQL\Utils;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "city"],
    "legalFields" => ["map", "title"],
  ];

  public function testValue()
  {
    $city = Utils::pages()->get("template=city");
    $query = 'mutation createPage($page: SkyscraperCreateInput!) {
      skyscraper: createSkyscraper (page: $page) {
        name
        id
        title
        map {
          lat
          lng
          address
          zoom
        }
      }
    }';
    $lat = 0.002303;
    $lng = -0.032713;
    $address = "1324 Manhattan, New York";
    $zoom = 2;
    $name = "new-building-with-location";
    $title = "New Building with Location";
    $variables = [
      "page" => [
        "parent" => "$city->id",
        "name" => $name,
        "title" => $title,
        "map" => [
          "lat" => $lat,
          "lng" => $lng,
          "address" => $address,
          "zoom" => $zoom,
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    $skyscraper = Utils::pages()->get("template=skyscraper, name=$name");
    $expectedMap = $skyscraper->map;
    $actualMap = $res->data->skyscraper->map;
    self::assertEquals(
      $skyscraper->title,
      $res->data->skyscraper->title,
      "Creates skyscraper page with correct title."
    );
    self::assertEquals(
      $skyscraper->name,
      $res->data->skyscraper->name,
      "Creates skyscraper page with correct name."
    );
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
