<?php

/**
 * Updates page properly
 * 
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeMapMarkerCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['map'],
  ];

  use AccessTrait;

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = 'mutation updatePage($id: String!, $page: SkyscraperUpdateInput!) {
      skyscraper: updateSkyscraper (id: $id, page: $page) {
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
      'id' => $skyscraper->id,
      'page' => [
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
    $this->assertEquals($skyscraper->id, $res->data->skyscraper->id, 'Updates the correct page.');
    $this->assertEquals($expectedMap->lat, $actualMap->lat, 'Updates lat correctly.');
    $this->assertEquals($expectedMap->lng, $actualMap->lng, 'Updates lng correctly.');
    $this->assertEquals($expectedMap->address, $actualMap->address, 'Updates address correctly.');
    $this->assertEquals($expectedMap->zoom, $actualMap->zoom, 'Updates zoom correctly.');
  }

}