<?php

/**
 * A field of a page cannot be updated if the
 * field is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\UpdatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class UpdatePageCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['height', 'floors', 'body'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation updatePage ($id: Int!, $page: SkyscraperUpdateInputType!) {
  		updateSkyscraper (id: $id, page: $page) {
  			title
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
  	$res = $this->execute($query, json_encode($variables));
    $this->assertEquals(1, count($res->errors), '`title` field is invalid for SkyscraperUpdateInputType if `title` field is not legal.');
  	$this->assertTrue($skyscraper->title !== $variables['page']['title'], 'updateSkyscraper does not update the `title`.');
  }

}