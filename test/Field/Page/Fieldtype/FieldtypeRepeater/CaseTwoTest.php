<?php

namespace ProcessWire\GraphQL\Test\FieldtypeRepeater;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["home", "list-all"],
    "legalFields" => ["slides", "title", "body", "selected"],
  ];

  public static function setUpBeforeClass(): void
  {
    parent::setUpBeforeClass();
    Utils::templates()->get("name=list-all")->noParents = "";
  }

  public static function tearDownAfterClass(): void
  {
    Utils::templates()->get("name=list-all")->noParents = "1";
    parent::tearDownAfterClass();
  }

  public function testValue()
  {
    $query = 'mutation createPage ($page: ListAllCreateInput!){
			createListAll(page:$page) {
				id
				name
				title
				slides {
					getTotal,
					list{
						id
						name
						title
					}
				}
			}
		}';
    $variables = [
      "page" => [
        "parent" => "/",
        "name" => "list-all-new",
        "title" => "List All New",
        "slides" => [
          "add" => [
            [
              "title" => "Slide 1",
              "body" => "<p>Awesome buildings!</p>",
              "selected" => "title*=awesome",
            ],
            [
              "title" => "Slide 2",
              "body" => "<p>Insane buildings!</p>",
              "selected" => "title*=insane",
            ],
          ],
        ],
      ],
    ];
    $res = self::execute($query, $variables);
    self::assertEquals(
      2,
      count($res->data->createListAll->slides->list),
      "Creates wrong amount of repeater items."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
