<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class AvailableTest extends GraphqlTestCase
{
  /**
   * + For Editor.
   * + The tamplet is legal.
   * + The user has edit permission for the template.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
            "editRoles" => ["editor"], // <-- has edit permission
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathExists(
      ["Mutation", "updateSkyscraper"],
      "The update field should be available for editor if the target template is legal and user has edit permission to it."
    );
  }
}
