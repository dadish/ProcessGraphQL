<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;

class FieldtypeFileTest extends GraphQLTestCase {
	
  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = ['architect'];
    Utils::module()->legalFields = ['resume'];
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    Utils::session()->logout();
  }

  public function testValue()
  {
  	$architect = Utils::pages()->get("template=architect, resume.count>0");
  	$query = "{
  		architect (s: \"id=$architect->id\") {
  			list {
  				resume {
  					list {
  						url
  					}
  				}
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	print_r($res);
  	// $this->assertEquals($architect->email, $res->data->architect->list[0]->email, 'Retrieves email value.');
  }

}