<?php

/**
 * A page cannot be created if parent page is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\NullPage;

class CreatePageCaseFiveTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['basic-page'],
    'legalFields' => ['title'],
  ];

	
  public function testValue()
  {
  	$query = 'mutation createPage ($page: BasicPageCreateInput!) {
  		createBasicPage (page: $page) {
  			name
				id
				title
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4121", // city page
				"name" => "not-created-basic-page",
				"title" => "New Basic Page"
  		]
  	];
		$res = self::execute($query, $variables);
		$newBuildingSky = Utils::pages()->get("name=not-created-basic-page");
		assertEquals(1, count($res->errors), 'createBasicPage does not resolve if parent page template is not legal.');
		assertInstanceOf(NullPage::class, $newBuildingSky, 'createBasicPage does not create a page.');
  }

}