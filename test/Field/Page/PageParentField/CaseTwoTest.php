<?php

/**
 * When user got access to requested page template but not
 * to the parent's template. The `parent` field returns
 * null.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageParentFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
    'legalPageFields' => ['parent'],
  ];

	
  public function testValue()
  {
  	$city = Utils::pages()->get("template=city");
  	$query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				parent {
            name
          }
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertTrue(is_null($res->data->city->list[0]->parent), 'Returns null when no access to parent page template.');
  }

}