<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateEditTest extends GraphqlTestCase
{
  /**
   * + For Editor.
   * + The tamplet is legal.
   * - The user has no edit permission for the template.
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
            // 'editRoles' => ['editor'], // <-- user has no edit permission
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "updateCity"],
      "The update field should not be available if user has no edit permission for the target template."
    );
  }
}
