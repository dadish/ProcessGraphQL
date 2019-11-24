<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


class EditorTrashNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For editor
   * + Has all required permissions.
   * - There is no legal template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      // 'legalTemplates' => ['city'], // <-- there is no legal template for the user to trash
      'access' => [
        'templates' => [
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-delete']
            ]
          ]
        ],
      ]
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(['Mutation', 'trash'], 'mutation.trash field is not available if there is ni legal templates.');
  }
}