<?php

/**
 * A page cannot be updated if it's template is not
 * legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\UpdatePage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["featured", "height", "floors", "body"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = 'mutation updatePage ($page: SkyscraperUpdateInput!) {
      updateSkyscraper (page: $page) {
        title
      }
    }';
    $variables = [
      "page" => [
        "id" => $skyscraper->id,
        "title" => "Old Building Sky",
      ],
    ];
    $res = self::execute($query, $variables);
    self::assertEquals(
      2,
      count($res->errors),
      "updateSkyscraper is not available if `skyscraper` template is not legal."
    );
    assertStringContainsString(
      "SkyscraperUpdateInput",
      $res->errors[0]->message
    );
    assertStringContainsString("updateSkyscraper", $res->errors[1]->message);
    self::assertTrue(
      $skyscraper->title !== $variables["page"]["title"],
      "updateSkyscraper does not update the `title`."
    );
  }
}
