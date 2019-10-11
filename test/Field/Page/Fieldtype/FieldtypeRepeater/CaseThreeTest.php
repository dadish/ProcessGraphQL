<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeRepeaterCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['home', 'list-all'],
    'legalFields' => ['slides', 'title', 'body', 'selected'],
  ];

	use AccessTrait;

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		Utils::templates()->get("name=list-all")->noParents = '';
	}

	public static function tearDownAfterClass()
	{
		Utils::templates()->get("name=list-all")->noParents = '1';
		parent::tearDownAfterClass();
	}

  public function testValue()
  {
		$page = Utils::pages()->get("template=list-all, slides.count=3");
		$this->assertEquals(3, count($page->slides));

  	$query = 'mutation updatePage ($id: ID!, $page: ListAllUpdateInput!){
			updateListAll(id: $id, page:$page) {
				slides {
					getTotal,
					list{
						id
					}
				}
			}
		}';
		$variables = [
			"id" => $page->id,
			"page" => [
				"slides" => [
					"remove" => [5754]
				]
			]
		];

		$res = self::execute($query, $variables);
  	$this->assertEquals(
			2,
  		$res->data->updateListAll->slides->getTotal,
  		'Removes the correct amount of repeaters.'
		);
		$this->assertEquals(
			"5755",
			$res->data->updateListAll->slides->list[0]->id,
			"Removes the correct repeater items."
		);
	}
}