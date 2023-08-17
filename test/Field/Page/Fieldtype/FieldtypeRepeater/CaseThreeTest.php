<?php

namespace ProcessWire\GraphQL\Test\FieldtypeRepeater;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
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
    $page = Utils::pages()->get("template=list-all, slides.count=3");
    self::assertEquals(3, count($page->slides));

    $query = 'mutation updatePage ($page: ListAllUpdateInput!){
			updateListAll(page:$page) {
				slides {
					getTotal,
					list{
						id
					}
				}
			}
		}';
    $variables = [
      "page" => [
        "id" => $page->id,
        "slides" => [
          "remove" => [5754],
        ],
      ],
    ];

    $res = self::execute($query, $variables);
    self::assertEquals(
      2,
      $res->data->updateListAll->slides->getTotal,
      "Removes the correct amount of repeaters."
    );
    self::assertEquals(
      "5755",
      $res->data->updateListAll->slides->list[0]->id,
      "Removes the correct repeater items."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
