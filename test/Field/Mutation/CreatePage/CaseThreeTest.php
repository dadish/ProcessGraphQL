<?php

/**
 * If everything ok, createSkyscraper should
 * create a skyscraper page
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "city"],
    "legalFields" => ["title", "featured", "height", "floors", "body"],
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
        "title" => "New Building Sky",
      ],
    ];
    $res = self::execute($query, $variables);
    $newBuildingSky = Utils::pages()->get("name=created-building-sky");
    self::assertEquals(
      $variables["page"]["name"],
      $res->data->skyscraper->name,
      "createSkyscraper returns correct values in response."
    );
    self::assertEquals(
      $newBuildingSky->id,
      $res->data->skyscraper->id,
      "createSkyscraper creates skyscraper page if everything is ok."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
