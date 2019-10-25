<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageTemplateFieldTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['template'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				template
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->template, $res->data->skyscraper->list[0]->template, 'Retrieves `template` field of the page.');
  }

}