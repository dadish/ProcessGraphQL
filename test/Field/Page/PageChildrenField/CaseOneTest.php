<?php

/**
 * When user got access to both requested page template
 * and it's childrens's template. The `children` field returns
 * the children pages.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\PageFieldAccessTrait;

class PageChildrenFieldCaseOneTest extends GraphQLTestCase {

  const TEMPLATE_NAME = ['city', 'skyscraper'];
  const PAGE_FIELD_NAME = 'children';

  use PageFieldAccessTrait;
	
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
  	$res = $this->execute($query);
    $this->assertEquals($city->children->count, count($res->data->city->list[0]->children->list), 'Retrieves children pages.');
  	$this->assertEquals($city->children[0]->name, $res->data->city->list[0]->children->list[0]->name, 'Retrieves children in correct order.');
  }

}