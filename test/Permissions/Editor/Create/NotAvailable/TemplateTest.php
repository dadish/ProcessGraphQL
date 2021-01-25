<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * + Editor has all required permissions.
   * - But the target template is not legal
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");

    return [
      "login" => "editor",

      // target template is not legal
      // if we add skyscraper below the test should fail
      "legalTemplates" => ["city"],
      "legalFields" => ["title"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => [$editorRole->id],
            "editRoles" => [$editorRole->id],
            "createRoles" => [$editorRole->id],
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
