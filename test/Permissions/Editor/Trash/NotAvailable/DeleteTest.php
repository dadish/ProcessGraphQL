<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Trash\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class DeleteTest extends GraphqlTestCase
{
  /**
   * + For editor
   * + The target template is legal.
   * + Has all required permissions.
   * - The user has no page-delete permission.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["city"],
      "access" => [
        "templates" => [
          [
            "name" => "city",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "rolesPermissions" => [
              // 'editor' => ['page-delete'] // <-- user has no page-delete permission.
              // 'editor' => ['page-edit-trash-created'] // <-- user has no page-edit-trash-created permission.
            ],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "trash"],
      "mutation.trash field is not available if user has no page-delete permission."
    );
  }
}
