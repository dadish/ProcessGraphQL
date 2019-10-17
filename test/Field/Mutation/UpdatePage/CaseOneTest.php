<?php

/**
 * A page cannot be updated if it's template is not
 * legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\UpdatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class UpdatePageCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
    'legalFields' => ['featured', 'height', 'floors', 'body'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation updatePage ($id: Int!, $page: SkyscraperUpdateInput!) {
  		updateSkyscraper (id: $id, page: $page) {
  			title
  		}
  	}';
  	$variables = [
  		"page" => [
				"title" => "Old Building Sky"
  		],
      "id" => $skyscraper->id
  	];
		$res = self::execute($query, $variables);
		$this->assertEquals(2, count($res->errors), 'updateSkyscraper is not available if `skyscraper` template is not legal.');
		$this->assertStringContainsString('SkyscraperUpdateInput', $res->errors[0]->message);
		$this->assertStringContainsString('updateSkyscraper', $res->errors[1]->message);
  	$this->assertTrue($skyscraper->title !== $variables['page']['title'], 'updateSkyscraper does not update the `title`.');
  }

}