<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   * - The field is not legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["images"], // <-- the title field is not legal
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
          ],
        ],
        "fields" => [
          [
            "name" => "title",
            "viewRoles" => ["editor"],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Query", "skyscraper", "list", "title"],
      "title field should not be available if title field is not legal."
    );
  }
}
