<?php

namespace ProcessWire\GraphQL\Test\Performance;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

/**
 * @group performance
 */
class DbShortQueryCountTest extends GraphQLTestCase
{
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["architect", "skyscraper"],
      "legalFields" => [
        "title",
        "options",
        "images",
        "architects",
        "born",
        "resume",
        "height",
        "floors",
        "year",
      ],
      "maxLimit" => 1000,
    ];
  }

  public static $shortQuery = [
    "query" => 'query getSkyscrapers($selector: Selector!) {
      skyscraper(s: $selector) {
        getTotal
        list{
          id
          title
        }
      }
    }',
    "variables" => [
      "selector" => "architects.count>1",
    ],
  ];

  public function testShortVsLongQuery()
  {
    $query = self::$shortQuery;
    $queryCountStart = count(Utils::database()->queryLog());
    self::execute($query["query"], $query["variables"]);
    $queryCountEnd = count(Utils::database()->queryLog());
    $queryCount = $queryCountEnd - $queryCountStart;

    \ProcessWire\GraphQL\log($queryCount, "ShortQueryCount");
    self::assertGreaterThan($queryCountStart, $queryCountEnd);
  }
}
