<?php

namespace ProcessWire\GraphQL\Test\FieldtypePage;

/**
 * Returns multiple values as expected.
 */
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "architect"],
    "legalFields" => ["architects"],
  ];

  public function testValue()
  {
    $query = 'query getSkyscrapers($s: Selector!){
      skyscraper (s: $s) {
        list {
          architects {
            list {
              id,
            }
          }
        }
      }
    }';
    $variables = [
      "s" => "architects.count>4, limit=5",
    ];
    $res = self::execute($query, $variables);
    self::assertGreaterThan(
      1,
      count($res->data->skyscraper->list[0]->architects->list),
      "Returns empty list."
    );
  }
}
