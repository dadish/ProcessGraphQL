<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageIdFieldTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['id'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				id
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->id, $res->data->skyscraper->list[0]->id, 'Retrieves `id` field of the page.');
  }

}