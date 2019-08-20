<?php

/**
 * When user got access to both requested page template
 * and it's child's template. The `child` field returns
 * the child page.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageChildFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city', 'skyscraper'],
    'legalPageFields' => ['child'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$city = Utils::pages()->get("template=city");
  	$query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				child {
            name
          }
  			}
  		}
  	}";
    $res = self::execute($query);
  	$this->assertEquals($city->child->name, $res->data->city->list[0]->child->name, 'Retrieves child page.');
  }

}