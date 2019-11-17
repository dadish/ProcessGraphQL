<?php

namespace ProcessWire\GraphQL\Test;

use ProcessWire\GraphQL\Test\GraphQLTestCase;

class DbQueryCountTest extends GraphQLTestCase {

  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['architect', 'skyscraper'],
      'legalFields' => [
        'title', 'options', 'images',
        'architects', 'born', 'resume',
        'height', 'floors', 'year'
      ],
    ];
  }

  /**
   * The lowest database query count so far. Taken from executing the $performanceQuery query.
   *
   * @var integer
   */
  public static $bestQueryCount = 188;

  /**
   * The query we use to test the performance of the module by counting
   * the queries to the database. 
   *
   * @var string
   */
  public static $performanceQuery = '{
    skyscraper(s: "architects.count>1") {
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
  }';

  public function testDbQueryCount() {
    $queryCountStart = \ProcessWire\Database::getQueryLog();
    self::execute(self::$performanceQuery);
    $queryCountEnd = \ProcessWire\Database::getQueryLog();
    $queryCount = count($queryCountEnd) - count($queryCountStart);

    // report
    $queryCountReport = "Query Count:\n$queryCount\n";
    $improvementPercent = (1 - ($queryCount / self::$bestQueryCount)) * 100;
    $performanceChangeReport = "\nPerformance Improvement\n$improvementPercent%";
    \ProcessWire\GraphQL\log("$queryCountReport$performanceChangeReport");

    // assert
    assertLessThanOrEqual(self::$bestQueryCount, $queryCount);
    assertGreaterThanOrEqual(self::$bestQueryCount / 5, $queryCount, "It can't be true! Performance increased five times!");
  }
}