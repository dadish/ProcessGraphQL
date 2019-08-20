<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeTextareaTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['body'],
  ];
  const FIELD_NAME = 'body';
  const FIELD_TYPE = 'FieldtypeTextarea';

  use FieldtypeTestTrait;
  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper, body!=''");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				body
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->body, $res->data->skyscraper->list[0]->body, 'Retrieves body value.');
  }

}