<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For Editor.
   * + The user has edit permission for the template.
   * - The template is not legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"], // <-- template "city" is not legal.
      "access" => [
        "templates" => [
          [
            "name" => "city",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
          ],
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "updateCity"],
      "The update field should not be available for user if the target template is not legal."
    );
  }
}
