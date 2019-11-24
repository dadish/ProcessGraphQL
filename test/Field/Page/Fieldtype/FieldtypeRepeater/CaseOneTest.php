<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeRepeaterCaseOneTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['list-all'],
    'legalFields' => ['slides', 'title'],
  ];
  const FIELD_NAME = 'slides';
  const FIELD_TYPE = 'FieldtypeRepeater';

  use FieldtypeTestTrait;

  public function testValue()
  {
  	$p = Utils::pages()->get("template=list-all, slides.count>0");
  	$query = "{
  		listAll (s: \"id=$p->id\") {
  			list {
  				slides {
  					list {
  						id
							name
							title
  					}
  				}
  			}
  		}
  	}";
		$res = self::execute($query);
  	assertEquals(
  		count($p->slides),
  		count($res->data->listAll->list[0]->slides->list),
  		'Returns correct repeater items count.'
		);
		assertEquals(
			$p->slides[0]->id,
			$res->data->listAll->list[0]->slides->list[0]->id,
			'Returns correct id for the first repeater item'
		);
		assertEquals(
			$p->slides[0]->title,
			$res->data->listAll->list[0]->slides->list[0]->title,
			'Returns correct title for the first repeater item'
		);
	}
}