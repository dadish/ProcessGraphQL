<?php

/**
 * A page cannot be created if not all required
 * fields are legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class CreatePageCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper', 'city'],
    'legalFields' => ['featured', 'height', 'floors', 'body'],
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
  	$this->assertEquals(1, count($res->errors), 'createSkyscraper is not available if required `title` field is not legal.');
  }

}