<?php

/**
 * If user has access to necessary templates then
 * find returns matched pages down the page's decendants
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageFindFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city', 'skyscraper'],
    'legalPageFields' => ['find'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$city = Utils::pages()->get("template=city");
  	$query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				bankBuildings: find (s: \"name*=bank\") {
            getTotal
            list {
              name
            }
          }
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $bankBuildings = $city->find("name*=bank");
    $this->assertEquals(
      $bankBuildings->count(),
      $res->data->city->list[0]->bankBuildings->getTotal,
      'Retrieves correct amount of pages.'
    );
  	$this->assertEquals(
      $bankBuildings->first()->name,
      $res->data->city->list[0]->bankBuildings->list[0]->name,
      'Filters pages correctly.'
    );
  }

}