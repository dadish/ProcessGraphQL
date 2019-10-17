<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeTextTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['freebase_guid'],
  ];
  const FIELD_NAME = 'freebase_guid';
  const FIELD_TYPE = 'FieldtypeText';

  use FieldtypeTestTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper, freebase_guid!=''");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				freebase_guid
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->freebase_guid, $res->data->skyscraper->list[0]->freebase_guid, 'Retrieves freebase_guid value.');
  }

}