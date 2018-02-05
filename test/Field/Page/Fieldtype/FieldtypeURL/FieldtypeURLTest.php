<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeURLTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['home'],
    'legalFields' => ['sponsor'],
  ];
  const FIELD_NAME = 'sponsor';
  const FIELD_TYPE = 'FieldtypeURL';

  use FieldtypeTestTrait;
  use AccessTrait;
	
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
  	$res = $this->execute($query);
  	$this->assertEquals($home->sponsor, $res->data->home->list[0]->sponsor, 'Retrieves sponsor value.');
  }

}