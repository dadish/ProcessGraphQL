<?php

/**
 * A page cannot be created if allowed parent page is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\NullPage;

class CreatePageCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['title', 'height', 'floors', 'body'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$query = 'mutation createPage ($page: SkyscraperCreateInput!) {
  		createSkyscraper (page: $page) {
  			name
  			id
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4121",
				"name" => "not-created-building-sky",
				"title" => "New Building Sky"
  		]
  	];
		$res = self::execute($query, $variables);
		$newBuildingSky = Utils::pages()->get("name=not-created-building-sky");
		$this->assertEquals(2, count($res->errors), 'createSkyscraper does not exist if allowed parent page is not legal.');
		$this->assertStringContainsString('SkyscraperCreateInput', $res->errors[0]->message);
		$this->assertStringContainsString('createSkyscraper', $res->errors[1]->message);
    $this->assertInstanceOf(NullPage::class, $newBuildingSky, 'createSkyscraper does not create a page.');
  }

}