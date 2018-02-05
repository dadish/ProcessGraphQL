<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\PageFieldAccessTrait;

class PageChildFieldTest extends GraphQLTestCase {

  const TEMPLATE_NAME = ['city', 'skyscraper'];
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
  	$this->assertEquals($city->child->name, $res->data->city->list[0]->child->name, 'Retrieves child page.');
  }

}