<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNotAllowedParentTemplateTest extends GraphqlTestCase {

  /**
   * + The template can be created under any parent.
   * - The target parent template is not legal
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['search'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => 0,
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
        'name' => 'search-new',
        'title' => 'Search New'
      ]
    ];

    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Should not allow to create a page if parent template is not legal.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}