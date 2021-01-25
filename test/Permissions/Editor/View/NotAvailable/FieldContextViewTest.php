<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldContextViewTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   * + The field is legal.
   * + The user has view permission for the field.
   * - The user's view permission for the field is reversed in the template context.
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
            "viewRoles" => ["editor"],
          ],
          [
            "name" => "title",
            "context" => "skyscraper",
            "viewRoles" => [], // <-- view permission is revoked in the template context
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Query", "skyscraper", "list", "title"],
      '"title" field should not be available if user view permission for the "title" field is revoked in the template context.'
    );
  }
}
