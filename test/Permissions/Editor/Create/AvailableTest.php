<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Create;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class AvailableTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The template should be legal.
   * + The configured parent template should be legal.
   * + All the required fields should be legal.
   * + Editor should have all required permissions.
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
    assertTypePathExists(
      ["Mutation", "createSkyscraper"],
      "createSKyscrpaer mutation field should be available."
    );
  }
}
