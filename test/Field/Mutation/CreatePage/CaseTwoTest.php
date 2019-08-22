<?php

/**
 * A page cannot be created if parent page is not legal
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
		$this->assertEquals(1, count($res->errors), 'createSkyscraper does not work if parent page is not legal.');
		$this->assertStringContainsString('parent', $res->errors[0]->message);
    $this->assertInstanceOf(NullPage::class, $newBuildingSky, 'createSkyscraper does not create a page.');
  }

}