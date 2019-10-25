<?php

/**
 * Creates page properly
 * 
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeMapMarkerCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'city'],
    'legalFields' => ['map', 'title'],
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
    $address = '1324 Manhattan, New York';
    $zoom = 2;
    $name = "new-building-with-location";
    $title = "New Building with Location";
    $variables = [
      'page' => [
        'parent' => "$city->id",
        'name' => $name,
        'title' => $title,
        'map' => [
          'lat' => $lat,
          'lng' => $lng,
          'address' => $address,
          'zoom' => $zoom,
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    $skyscraper = Utils::pages()->get("template=skyscraper, name=$name");
    $expectedMap = $skyscraper->map;
    $actualMap = $res->data->skyscraper->map;
    $this->assertEquals($skyscraper->title, $res->data->skyscraper->title, 'Creates skyscraper page with correct title.');
    $this->assertEquals($skyscraper->name, $res->data->skyscraper->name, 'Creates skyscraper page with correct name.');
    $this->assertEquals($expectedMap->lat, $actualMap->lat, 'Retreives correct lat.');
    $this->assertEquals($expectedMap->lng, $actualMap->lng, 'Retreives correct lng.');
    $this->assertEquals($expectedMap->address, $actualMap->address, 'Retreives correct address.');
    $this->assertEquals($expectedMap->zoom, $actualMap->zoom, 'Retreives correct zoom.');
  }

}