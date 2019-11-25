<?php

/**
 * Updates page properly
 * 
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeMapMarkerCaseThreeTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['map'],
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
    $address = '23576 Broadway, Chicago';
    $zoom = 3;
    $name = "updated-building-with-location";
    $variables = [
      'page' => [
        'id' => $skyscraper->id,
        'map' => [
          'lat' => $lat,
          'lng' => $lng,
          'address' => $address,
          'zoom' => $zoom,
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    $expectedMap = $skyscraper->map;
    $actualMap = $res->data->skyscraper->map;
    assertEquals($skyscraper->id, $res->data->skyscraper->id, 'Updates the correct page.');
    assertEquals($expectedMap->lat, $actualMap->lat, 'Updates lat correctly.');
    assertEquals($expectedMap->lng, $actualMap->lng, 'Updates lng correctly.');
    assertEquals($expectedMap->address, $actualMap->address, 'Updates address correctly.');
    assertEquals($expectedMap->zoom, $actualMap->zoom, 'Updates zoom correctly.');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}