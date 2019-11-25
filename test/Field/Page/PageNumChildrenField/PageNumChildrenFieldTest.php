<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageNumChildrenFieldTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['numChildren'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				numChildren
  			}
  		}
  	}";
  	$res = self::execute($query);
		assertEquals($skyscraper->numChildren, $res->data->skyscraper->list[0]->numChildren, 'Retrieves `numChildren` field of the page.');
		assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}