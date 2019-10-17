<?php

/**
 * Retreives correct values.
 * 
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;

class FieldtypeMapMarkerCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['map'],
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
    $this->assertEquals($expectedMap->lat, $actualMap->lat, 'Retreives correct lat.');
    $this->assertEquals($expectedMap->lng, $actualMap->lng, 'Retreives correct lng.');
    $this->assertEquals($expectedMap->address, $actualMap->address, 'Retreives correct address.');
    $this->assertEquals($expectedMap->zoom, $actualMap->zoom, 'Retreives correct zoom.');
  }

}