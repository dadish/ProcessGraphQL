<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\Permissions;


class EditorTrashAvailableEditTrashCreatedPermissionTest extends GraphqlTestCase {

  /**
   * + For editor
   * + The template is legal.
   * + The user has page-edit-trash-created permission on a legal template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['architect'],
      'access' => [
        'templates' => [
          [
            'name' => 'architect',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-edit-trash-created'], // <-- has page-edit-trash-created permission
            ]
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    assertTypePathExists(['Mutation', 'trash']);
  }
}