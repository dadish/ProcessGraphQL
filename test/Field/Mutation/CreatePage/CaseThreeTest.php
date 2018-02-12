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
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation createPage ($page: SkyscraperCreateInputType!) {
  		skyscraper: createSkyscraper (page: $page) {
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
  	$this->assertEquals($variables['page']['name'], $res->data->skyscraper->name, 'createSkyscraper creates skyscraper page if everything is ok.');
  }

}