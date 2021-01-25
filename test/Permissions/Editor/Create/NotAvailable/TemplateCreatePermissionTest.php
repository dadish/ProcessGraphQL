<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class TemplateCreatePermissionTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * + The target template is legal
   * - Editor does not have page-create permission on target template.
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");

    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper", "city"],
      "legalFields" => ["title"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => [$editorRole->id],
            "editRoles" => [$editorRole->id],
            // no create roles thus cannot create a skyscraper
          ],
          [
            "name" => "city",
            "roles" => [$editorRole->id],
            "addRoles" => [$editorRole->id],
          ],
        ],
        "fields" => [
          [
            "name" => "title",
            "editRoles" => [$editorRole->id],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "createSkyscraper"],
      "Create field should not be available if target template is not legal."
    );
  }
}
