<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeURLTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['home'],
    'legalFields' => ['sponsor'],
  ];
  const FIELD_NAME = 'sponsor';
  const FIELD_TYPE = 'FieldtypeURL';

  use FieldtypeTestTrait;
	
  public function testValue()
  {
  	$home = Utils::pages()->get("template=home");
  	$query = "{
  		home (s: \"id=$home->id\") {
  			list {
  				sponsor
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($home->sponsor, $res->data->home->list[0]->sponsor, 'Retrieves sponsor value.');
  }

}