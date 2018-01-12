<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldAccessTrait;

class FieldtypePageTitleTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'skyscraper';
  const FIELD_NAME = 'title';
  const FIELD_TYPE = 'FieldtypePageTitle';

  use FieldtypeTestTrait;
  use FieldAccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				title
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	$this->assertEquals($skyscraper->title, $res->data->skyscraper->list[0]->title, 'Retrieves title value.');
  }

}