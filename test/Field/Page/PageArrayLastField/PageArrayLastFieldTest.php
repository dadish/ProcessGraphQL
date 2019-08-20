<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageArrayLastFieldTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
    'legalFields' => ['title'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$lastCity = Utils::pages()->find("template=city, limit=50")->last();
  	$query = "{
  		city {
  			last {
          name
          id
          title
  			}
  		}
  	}";
  	$res = self::execute($query);
    $this->assertEquals($lastCity->name, $res->data->city->last->name, 'Retrieves correct name of the last page.');
    $this->assertEquals($lastCity->id, $res->data->city->last->id, 'Retrieves correct id of the last page.');
  	$this->assertEquals($lastCity->title, $res->data->city->last->title, 'Retrieves correct title of the last page.');
  }

}