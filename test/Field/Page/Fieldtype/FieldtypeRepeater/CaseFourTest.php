<?php

namespace ProcessWire\GraphQL\Test\FieldtypeRepeater;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
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
    $slideId = 5755;
    $newTitle = "Slide 2 New Title";
    $newSelected = "title*=boblibob!";
    $page = Utils::pages()->get("template=list-all, slides.count=3");
    self::assertNotEquals($newTitle, $page->slides->get("id=$slideId")->title);
    self::assertNotEquals(
      $newSelected,
      $page->slides->get("id=$slideId")->selected
    );

    $query = 'mutation updatePage ($page: ListAllUpdateInput!, $slideSelector: Selector){
			updateListAll(page:$page) {
				slides(s: $slideSelector) {
					getTotal,
					list{
						id
						title
						selected
					}
				}
			}
		}';
    $variables = [
      "page" => [
        "id" => $page->id,
        "slides" => [
          "update" => [
            [
              "id" => 5755,
              "title" => $newTitle,
              "selected" => $newSelected,
            ],
          ],
        ],
      ],
      "slideSelector" => "id=$slideId",
    ];

    $res = self::execute($query, $variables);
    self::assertEquals(
      $newTitle,
      $res->data->updateListAll->slides->list[0]->title,
      "Updates the title correctly."
    );
    self::assertEquals(
      Utils::fields()->get("name=selected")->initValue . ", " . $newSelected,
      $res->data->updateListAll->slides->list[0]->selected,
      "Updates the selected correctly."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
