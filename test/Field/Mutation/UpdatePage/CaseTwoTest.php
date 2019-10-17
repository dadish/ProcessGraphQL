<?php

/**
 * A field of a page cannot be updated if the
 * field is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\UpdatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class UpdatePageCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['height', 'floors', 'body'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation updatePage ($id: ID!, $page: SkyscraperUpdateInput!) {
  		updateSkyscraper (id: $id, page: $page) {
  			name
  		}
  	}';
  	$variables = [
  		"page" => [
				"title" => "Old Building Sky",
        "height" => 353,
        "floors" => 84,
  		],
      "id" => $skyscraper->id
  	];
		$res = self::execute($query, $variables);
		$this->assertEquals(1, count($res->errors), '`title` field is invalid for SkyscraperUpdateInputType if `title` field is not legal.');
		$this->assertStringContainsString('title', $res->errors[0]->message);		
  	$this->assertTrue($skyscraper->title !== $variables['page']['title'], 'updateSkyscraper does not update the `title`.');
  }

}