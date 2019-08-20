<?php

/**
 * When user got access to requested page template but not
 * to the child's template. The `child` field returns
 * null.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageChildFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
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
  	$this->assertTrue(is_null($res->data->city->list[0]->child), 'Returns null when no access to child page template.');
  }

}