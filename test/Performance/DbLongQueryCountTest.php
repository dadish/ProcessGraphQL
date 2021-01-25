<?php

namespace ProcessWire\GraphQL\Test\Performance;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

/**
 * @group performance
 */
class DbLongQueryCountTest extends GraphQLTestCase
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

  /**
   * The query we use to test the performance of the module by counting
   * the queries to the database.
   *
   * @var string
   */
  public static $longQuery = [
    "query" => 'query getSkyscrapers($selector: Selector!) {
      skyscraper(s: $selector) {
        getTotal
        list{
          id
          title
          height
          floors
          year
          images {
            url
            width
            height
            description
          }
          architects{
            list{
              id
              title
              created
              born
              resume{
                url
                description
              }
            }
          }
        }
      }
    }',
    "variables" => [
      "selector" => "architects.count>1",
    ],
  ];

  public function testShortVsLongQuery()
  {
    $query = self::$longQuery;
    $queryCountStart = count(Utils::database()->queryLog());
    self::execute($query["query"], $query["variables"]);
    $queryCountEnd = count(Utils::database()->queryLog());
    $queryCount = $queryCountEnd - $queryCountStart;

    \ProcessWire\GraphQL\log($queryCount, "LongQueryCount");
    self::assertGreaterThan($queryCountStart, $queryCountEnd);
  }
}
