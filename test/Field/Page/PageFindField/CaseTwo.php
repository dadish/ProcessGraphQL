<?php

/**
 * If user does not have access to necessary templates
 * then find returns an empty list.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageFindFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
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
    $this->assertEquals(0, count($res->data->city->list[0]->bankBuildings->list), 'Returns empty list if no access.');
  }

}