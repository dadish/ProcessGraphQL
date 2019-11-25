<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeFileTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['architect'],
    'legalFields' => ['resume'],
  ];
  const FIELD_NAME = 'resume';
  const FIELD_TYPE = 'FieldtypeFile';

  use FieldtypeTestTrait;

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
		$res = self::execute($query);
  	assertEquals(
  		$architect->resume->first()->url,
  		$res->data->architect->list[0]->resume[0]->url,
  		'Retrieves files value.'
		);
		assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}