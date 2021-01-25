<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For Editor.
   * + The target template is not legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["architect", "city"], // <-- the skyscraper template is not legal
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Query", "skyscraper"],
      "skyscraper field should not be available if skyscraper template is not legal."
    );
  }
}
