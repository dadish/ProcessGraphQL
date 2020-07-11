<?php

namespace ProcessWire\GraphQL\Test;

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

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
      'maxLimit' => 1000,
    ];
  }

  /**
   * The query we use to test the performance of the module by counting
   * the queries to the database. 
   *
   * @var string
   */
  public static $longQuery = [
    'query' => 'query getSkyscrapers($selector: Selector!) {
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
    'variables' => [
      'selector' => 'architects.count>1'
    ]
  ];

  public function testShortVsLongQuery()
  {
    $query = self::$longQuery;
    $queryCountStart = \ProcessWire\Database::getQueryLog();
    self::execute($query['query'], $query['variables']);
    $queryCountEnd = \ProcessWire\Database::getQueryLog();
    $queryCount = count($queryCountEnd) - count($queryCountStart);

    \ProcessWire\GraphQL\log("QueryCount: $queryCount");
  }
}