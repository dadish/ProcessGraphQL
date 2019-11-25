<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypePageTitleTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['title'],
  ];
  const FIELD_NAME = 'title';
  const FIELD_TYPE = 'FieldtypePageTitle';

  use FieldtypeTestTrait;
	
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
    assertEquals($skyscraper->title, $res->data->skyscraper->list[0]->title, 'Retrieves title value.');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}