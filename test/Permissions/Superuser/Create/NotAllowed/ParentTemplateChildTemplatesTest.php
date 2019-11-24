<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCreateNotAllowedParentTemplateChildTemplatesTest extends GraphqlTestCase {

  /**
   * + The template can be created under any parent.
   * + The target parent template is legal
   * - The target parent tempate has ChildTemplates without target template.
   */
  public static function getSettings()
  {
    $listAll = Utils::pages()->get("template=list-all");
    return [
      'login' => 'admin',
      'legalTemplates' => ['home', 'search'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => 0,
            'noChildren' => 0, // search page can be created under another search page
          ],
          [
            'name' => 'home',
            'childTemplates' => [$listAll->id], // but we will try to create the search page under homepage
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    $query = 'mutation createPage($page: SearchCreateInput!) {
      createSearch(page: $page) {
        id
        name
        title
        template
      }
    }';

    $variables = [
      'page' => [
        'parent' => 1,
        'name' => 'search-2',
        'title' => 'Search 2'
      ]
    ];

    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Should not allow to create a page under the page with template that has childTemplates without target template.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}