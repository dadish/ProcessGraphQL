<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;


class EditorTrashAvailableDeletePermissionTest extends GraphqlTestCase {

  /**
   * + For editor
   * + The template is legal.
   * + The user has page delete permission on a legal template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city'],
      'access' => [
        'templates' => [
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-delete'] // <-- user has page-delete permission on this template
            ]
          ]
        ],
      ]
    ];
  }

  public function testPermission() {
    assertTypePathExists(['Mutation', 'trash']);
  }
}