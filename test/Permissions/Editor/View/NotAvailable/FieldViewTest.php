<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldViewTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   * + The field is legal.
   * - The user has no view permission for the field.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["title"],
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
            // 'viewRoles' => ['editor'], // <-- has no view permission
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Query", "skyscraper", "list", "title"],
      '"title" field should not be available if user has no view permission for the "title" field.'
    );
  }
}
