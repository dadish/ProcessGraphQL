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
  					url
  				}
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	$this->assertEquals(
  		$architect->resume->first()->url,
  		$res->data->architect->list[0]->resume[0]->url,
  		'Retrieves files value.'
  	);
  }

}