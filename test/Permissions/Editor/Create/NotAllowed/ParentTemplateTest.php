<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCreateNotAllowedParentTemplateTest extends GraphqlTestCase {

  /**
   * + For Editor
   * + Everything in line for createSearch field.
   * - But the target parent template is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['search'], // <-- no "home" template!
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => 0,
            'noChildren' => 0,
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'createRoles' => ['editor'],
          ],
          [
            'name' => 'home',
            'roles' => ['editor'],
            'addRoles' => ['editor'],
          ]
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
        'parent' => 1, // <-- setting a "home" as a parent.
        'name' => 'search',
        'title' => 'Search'
      ]
    ];

    $res = self::execute($query, $variables);
    $this->assertEquals(1, count($res->errors), 'Should not allow to create a page if parent template is not legal.');
    $this->assertStringContainsString('parent', $res->errors[0]->message);
  }
}