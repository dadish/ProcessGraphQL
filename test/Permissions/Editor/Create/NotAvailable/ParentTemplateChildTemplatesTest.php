<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class ParentTemplateChildTemplatesTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The template should be legal.
   * + The configured parent template should be legal.
   * + All the required fields should be legal.
   * + Editor should have all required permissions.
   * - But the configured parent template has childTemplates without target template id.
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");
    $architectTemplate = Utils::templates()->get("architect");

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
            "childTemplates" => [$architectTemplate->id],
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
      "Create field should not be available if configured parent template has childTemplates that does not match target template."
    );
  }
}
