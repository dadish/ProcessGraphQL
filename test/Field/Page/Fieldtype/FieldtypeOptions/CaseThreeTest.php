<?php

/**
 * You can pass a plain string for options field that
 * stores single option
 */

namespace ProcessWire\GraphQL\Test\FieldtypeOptions;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\NullPage;

class CaseThreeTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["cities", "city"],
    "legalFields" => ["options_single", "title"],
  ];

  public function testValue()
  {
    $name = "new-city";
    $title = "New City";
    $option = "Mon";
    $query = 'mutation createPage ($page: CityCreateInput!) {
  		createCity (page: $page) {
  			name
  			id
        title
        options_single {
          title
          value
          id
        }
  		}
  	}';
    $variables = [
      "page" => [
        "parent" => "4049",
        "name" => $name,
        "title" => $title,
        "options_single" => $option,
      ],
    ];
    $res = self::execute($query, $variables);
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");

    $newCity = Utils::pages()->get("template=city, name=$name");
    self::assertTrue(!$newCity instanceof NullPage, "New Page is created.");
    self::assertEquals($name, $newCity->name, "New Page has correct name.");
    self::assertEquals($title, $newCity->title, "New Page has correct title.");
    self::assertEquals(
      $option,
      $newCity->options_single->title,
      "New Page has correct option title."
    );
  }
}
