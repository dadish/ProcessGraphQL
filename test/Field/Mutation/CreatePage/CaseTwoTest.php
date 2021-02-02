<?php

/**
 * A page cannot be created if allowed parent page is not legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\CreatePage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\NullPage;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["title", "height", "floors", "body"],
  ];

  public function testValue()
  {
    $query = 'mutation createPage ($page: SkyscraperCreateInput!) {
      createSkyscraper (page: $page) {
        name
        id
      }
    }';
    $name = "not-created-building-sky";
    $variables = [
      "page" => [
        "parent" => "4121",
        "name" => $name,
        "title" => "New Building Sky",
      ],
    ];
    $res = self::execute($query, $variables);
    $newBuildingSky = Utils::pages()->get("name=$name");
    self::assertEquals(
      2,
      count($res->errors),
      // "createSkyscraper does not exist if allowed parent page is not legal."
      "Should have two errors."
    );
    self::assertStringContainsString(
      "SkyscraperCreateInput",
      $res->errors[0]->message,
      "'SkyscraperCreateInput' type should not exist."
    );
    self::assertStringContainsString(
      "createSkyscraper",
      $res->errors[1]->message,
      "'createSkyscraper' field should not exist."
    );
    self::assertInstanceOf(
      NullPage::class,
      $newBuildingSky,
      "'createSkyscraper' does not create a page."
    );
  }
}
