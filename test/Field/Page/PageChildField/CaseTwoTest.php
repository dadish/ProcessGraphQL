<?php

/**
 * When user got access to requested page template but not
 * to the child's template. The `child` field returns
 * null.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\PageFieldAccessTrait;

class PageChildFieldCaseTwoTest extends GraphQLTestCase {

  const TEMPLATE_NAME = ['city'];
  const PAGE_FIELD_NAME = 'child';

  use PageFieldAccessTrait;
	
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
  	$res = $this->execute($query);
  	$this->assertTrue(is_null($res->data->city->list[0]->child), 'Returns null when no access to child page template.');
  }

}