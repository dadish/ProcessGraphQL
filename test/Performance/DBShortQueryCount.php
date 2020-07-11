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

  public static $shortQuery = [
    'query' => 'query getSkyscrapers($selector: Selector!) {
      skyscraper(s: $selector) {
        getTotal
        list{
          id
          title
        }
      }
    }',
    'variables' => [
      'selector' => 'architects.count>1'
    ]
  ];

  public function testShortVsLongQuery()
  {
    $query = self::$shortQuery;
    $queryCountStart = \ProcessWire\Database::getQueryLog();
    self::execute($query['query'], $query['variables']);
    $queryCountEnd = \ProcessWire\Database::getQueryLog();
    $queryCount = count($queryCountEnd) - count($queryCountStart);

    \ProcessWire\GraphQL\log("QueryCount: $queryCount");
  }
}