<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeRepeaterCaseFiveTest extends GraphQLTestCase {

  const settings = [
    'login' => 'editor',
    'legalTemplates' => ['list-all'],
		'legalFields' => ['body', 'slides', 'title'],
		'access' => [
			'templates' => [
				[
					'name' => 'list-all',
					'roles' => ['editor']
				],
			],
			'fields' => [
				[
					'name' => 'body',
					'viewRoles' => ['editor']
				],
				[
					'name' => 'slides',
					'viewRoles' => ['editor']
				],
				[
					'name' => 'title',
					'viewRoles' => ['editor']
				],
			]
		]
  ];

  public function testValue()
  {
  	$p = Utils::pages()->get("template=list-all, slides.count>0");
  	$query = "{
  		listAll (s: \"id=$p->id\") {
  			list {
					title
  				slides {
  					list {
  						id
							name
							title
							body
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
		assertNotEmpty($p->slides[0]->title, '"title" field is empty for editor.');
		assertEquals(
			$p->slides[0]->title,
			$res->data->listAll->list[0]->slides->list[0]->title,
			'Returns correct title for the first repeater item'
		);
		assertNotEmpty($p->slides[0]->body, '"body" field is empty for editor.');
		assertEquals(
			$p->slides[0]->body,
			$res->data->listAll->list[0]->slides->list[0]->body,
			'Returns correct body for the first repeater item'
		);
		assertObjectNotHasAttribute('errors', $res, 'There are errors.');
	}
}