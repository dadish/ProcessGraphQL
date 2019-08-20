<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypePageSelectorTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['home'],
    'legalFields' => ['selected'],
  ];
  const FIELD_NAME = 'selected';
  const FIELD_TYPE = 'FieldtypeSelector';

  use AccessTrait;
  use FieldtypeTestTrait;
	
  public function testValue()
  {
  	$home = Utils::pages()->get("template=home");
  	$query = "{
  		home (s: \"id=$home->id\") {
  			list {
  				selected
  			}
  		}
  	}";
    $res = self::execute($query);
  	$this->assertEquals($home->selected, $res->data->home->list[0]->selected, 'Retrieves selector value.');
  }

}