<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldAccessTrait;

class FieldtypePageSelectorTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'home';
  const FIELD_NAME = 'selected';
  const FIELD_TYPE = 'FieldtypeSelector';

  use FieldtypeTestTrait;
  use FieldAccessTrait;
	
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
  	$res = $this->execute($query);
  	$this->assertEquals($home->selected, $res->data->home->list[0]->selected, 'Retrieves title value.');
  }

}