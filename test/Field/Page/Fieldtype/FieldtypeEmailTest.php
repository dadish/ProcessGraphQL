<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;

class FieldtypeEmailTest extends GraphQLTestCase {
	
  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = ['architect'];
    Utils::module()->legalFields = ['email'];
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    Utils::session()->logout();
  }

  public function testValue()
  {
  	$architect = Utils::pages()->get("template=architect");
  	$query = "{
  		architect (s: \"id=$architect->id\") {
  			list {
  				email
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	$this->assertEquals($architect->email, $res->data->architect->list[0]->email, 'Retrieves email value.');
  }

}