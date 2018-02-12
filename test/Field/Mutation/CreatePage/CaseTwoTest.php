<?php

/**
 * A page cannot be created if parent page is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class CreatePageCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['title', 'height', 'floors', 'body'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation createPage ($page: SkyscraperCreateInputType!) {
  		createSkyscraper (page: $page) {
  			name
  			id
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4121",
				"name" => "new-building-sky",
				"title" => "New Building Sky"
  		]
  	];
  	$res = $this->execute($query, json_encode($variables));
  	$this->assertEquals(1, count($res->errors), 'createSkyscraper is not available if parent page is not legal.');
  }

}