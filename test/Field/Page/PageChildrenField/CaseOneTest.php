<?php

/**
 * When user got access to both requested page template
 * and it's childrens's template. The `children` field returns
 * the children pages.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageChildrenFieldCaseOneTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city', 'skyscraper'],
    'legalPageFields' => ['children'],
    'maxLimit' => 500
  ];

	
  public function testValue()
  {
  	$city = Utils::pages()->get("template=city");
  	$query = "{
  		city (s: \"id=$city->id\") {
  			list {
  				children {
            list {
              name
            }
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    assertEquals($city->children->count, count($res->data->city->list[0]->children->list), 'Retrieves children pages.');
    assertEquals($city->children[0]->name, $res->data->city->list[0]->children->list[0]->name, 'Retrieves children in correct order.');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}