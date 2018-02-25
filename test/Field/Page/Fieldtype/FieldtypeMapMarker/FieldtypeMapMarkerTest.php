<?php

/**
 * Can't make this one work. Don't know why.
 * 
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeMapMarkerTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['map'],
  ];
  const FIELD_NAME = 'map';
  const FIELD_TYPE = 'FieldtypeMapMarker';

  use AccessTrait;

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
    $res = $this->execute($query);
    $expectedMap = $skyscraper->map;
    $actualMap = $res->data->skyscraper->list[0]->map;
    $this->assertEquals($expectedMap->lat, $actualMap->lat, 'Retreives correct lat.');
    $this->assertEquals($expectedMap->lng, $actualMap->lng, 'Retreives correct lng.');
    $this->assertEquals($expectedMap->address, $actualMap->address, 'Retreives correct address.');
    $this->assertEquals($expectedMap->zoom, $actualMap->zoom, 'Retreives correct zoom.');
  }

}