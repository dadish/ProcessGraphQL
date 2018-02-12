<?php

/**
 * If everything ok, createSkyscraper should
 * create a skyscraper page
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class CreatePageCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper', 'city'],
    'legalFields' => ['title', 'featured', 'height', 'floors', 'body'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$query = 'mutation createPage ($page: SkyscraperCreateInputType!) {
  		skyscraper: createSkyscraper (page: $page) {
  			name
  			id
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4121",
				"name" => "created-building-sky",
				"title" => "New Building Sky"
  		]
  	];
  	$res = $this->execute($query, json_encode($variables));
    $newBuildingSky = Utils::pages()->get("name=created-building-sky");
    $this->assertEquals($variables['page']['name'], $res->data->skyscraper->name, 'createSkyscraper returns correct values in response.');
  	$this->assertEquals($newBuildingSky->id, $res->data->skyscraper->id, 'createSkyscraper creates skyscraper page if everything is ok.');
  }

}