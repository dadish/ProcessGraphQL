<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create\NotAllowed;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class ParentTemplateNoChildrenTest extends GraphqlTestCase
{
  /**
   * + The template can be created under any parent.
   * + The target parent template is legal
   * - The target parent tempate has NoChildren checked.
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
            "noParents" => 0,
            "noChildren" => 0, // search page can be created under another search page
          ],
          [
            "name" => "home",
            "noChildren" => 1, // but we will try to create the search page under homepage
          ],
        ],
      ],
    ];
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
        "parent" => 1,
        "name" => "search-2",
        "title" => "Search 2",
      ],
    ];

    $res = self::execute($query, $variables);
    self::assertEquals(
      1,
      count($res->errors),
      "Should not allow to create a page under the page with template that has NoChildren checked."
    );
    assertStringContainsString("parent", $res->errors[0]->message);
  }
}
