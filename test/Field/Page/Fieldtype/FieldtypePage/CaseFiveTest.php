<?php

namespace ProcessWire\GraphQL\Test\FieldtypePage;

/**
 * Should properly handle FieldtypePage::derefAsPageArray option.
 */
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class CaseFiveTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "architect", "city"],
    "legalFields" => ["architects", "title"],
    "access" => [
      "fields" => [
        [
          "name" => "architects",
          "derefAsPage" => \ProcessWire\FieldtypePage::derefAsPageOrNullPage,
        ],
      ],
    ],
  ];

  public function testValue()
  {
    $architect = Utils::pages()->get(4476);
    $query = 'mutation createSkyscraper($page: SkyscraperCreateInput!){
      createSkyscraper (page: $page) {
        title,
        name,
        architects {
          list {
            id,
          }
        }
      }
    }';
    $name = "new-sky-test-158";
    $title = "New Sky Test 158";
    $variables = [
      "page" => [
        "name" => $name,
        "title" => $title,
        "parent" => "4114",
        "architects" => [
          "add" => [$architect->id],
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    $actual = $res->data->createSkyscraper;
    self::assertEquals($name, $actual->name, "Sets the correct name.");
    self::assertEquals($title, $actual->title, "Sets the correct title.");
    self::assertEquals(
      $architect->id,
      $actual->architects->list[0]->id,
      "Sets the correct architect."
    );
  }
}
