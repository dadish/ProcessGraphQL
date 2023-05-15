<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class AllowedTest extends GraphqlTestCase
{
  /**
   * + The template can have only one Page.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["home", "search"],
      "legalFields" => ["title"],
      "access" => [
        "templates" => [
          [
            "name" => "search",
            "noParents" => -1,
          ],
        ],
      ],
    ];
  }

  /**
   * Delete the single search page so we can create it again.
   */
  public static function setUpBeforeClass(): void
  {
    $searchPage = Utils::pages()->get("template=search");
    if ($searchPage->id) {
      $searchPage->delete();
    }
    parent::setUpBeforeClass();
  }

  public function testPermission()
  {
    $query = 'mutation createPage($page: SearchCreateInput!) {
      createSearch(page: $page) {
        id
        name
        title
        template
      }
    }';

    $variables = [
      "page" => [
        "parent" => "1",
        "name" => "search",
        "title" => "Search",
      ],
    ];

    $res = self::execute($query, $variables);
    self::assertEquals(
      "search",
      $res->data->createSearch->name,
      "Should allow to create a page with OnlyOne checked if there is not already a page with that template."
    );
    self::assertEquals("Search", $res->data->createSearch->title);
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
