<?php

/**
 * If required field is not provided the GraphQL should not
 * resolve the request.
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class CreatePageCaseFourTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'city'],
    'legalFields' => ['title', 'featured', 'height', 'floors', 'body'],
  ];

	
  public function testValue()
  {
  	$query = 'mutation createPage ($page: SkyscraperCreateInput!) {
  		skyscraper: createSkyscraper (page: $page) {
  			name
  			id
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4121",
				"name" => "created-building-sky",
  		]
  	];
		$res = self::execute($query, $variables);
		$this->assertStringContainsString(
			'Field value.title of required type PageTitle!',
			$res->errors[0]->message
		);
  }
}