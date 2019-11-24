<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCreateNotAllowedTemplateOnlyOneTest extends GraphqlTestCase {

  /**
   * + The template can have only one Page.
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get('editor');
    return [
      'login' => 'editor',
      'legalTemplates' => ['home', 'search'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => -1,
            'roles' => [$editorRole->id],
            'editRoles' => [$editorRole->id],
            'createRoles' => [$editorRole->id],
          ],
          [
            'name' => 'home',
            'roles' => [$editorRole->id],
            'addRoles' => [$editorRole->id],
          ]
        ],
        'fields' => [
          [
            'name' => 'title',
            'viewRoles' => [$editorRole->id],
            'editRoles' => [$editorRole->id],
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
        'name' => 'search-3',
        'title' => 'Search 3'
      ]
    ];

    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Should not allow to create a page with OnlyOne checked if there is already a page with that template.');
    assertStringContainsString('Only one', $res->errors[0]->message);
  }
}