<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorCreateNotAllowedTemplateParentTemplateTest extends GraphqlTestCase {

  /**
   * + The template can have only one Page.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['home', 'search', 'list-all'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => 0,
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'createRoles' => ['editor'],
            'parentTemplates' => ['list-all'], // <-- testing this rule!
          ],
          [
            'name' => 'home',
            'roles' => ['editor'],
            'addRoles' => ['editor'],
          ],
          [
            'name' => 'list-all',
            'noChildren' => 0,
            'roles' => ['editor'],
            'addRoles' => ['editor'],
          ],
        ],
        'fields' => [
          [
            'name' => 'title',
            'viewRoles' => ['editor'],
            'editRoles' => ['editor'],
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
    assertEquals(1, count($res->errors), 'Should not allow to create a page target template has parentTemplates without target parent.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}