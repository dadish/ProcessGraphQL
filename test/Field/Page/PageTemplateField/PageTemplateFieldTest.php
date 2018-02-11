<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageTemplateFieldTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['template'],
  ];

  use AccessTrait;
	
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
  	$res = $this->execute($query);
  	$this->assertEquals($skyscraper->template, $res->data->skyscraper->list[0]->template, 'Retrieves `template` field of the page.');
  }

}