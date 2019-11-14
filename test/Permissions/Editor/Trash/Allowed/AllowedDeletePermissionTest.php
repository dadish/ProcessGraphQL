<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorTrashAllowedDeletePermissionTest extends GraphqlTestCase {

  /**
   * + For editor
   * + The template is legal.
   * + The user has all required permissions
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-delete'] // <-- has page-delete permission
            ]
          ]
        ],
      ]
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = 'mutation trashPage($id: ID!) {
      trash(id: $id) {
        id
        name
      }
    }';
    $variables = [
      'id' => $skyscraper->id,
    ];

    assertFalse($skyscraper->isTrash());
    $res = self::execute($query, $variables);
    assertEquals($res->data->trash->id, $skyscraper->id, 'Trashes the page.');
    assertTrue($skyscraper->isTrash(), 'Trashes the correct page.');
  }
}