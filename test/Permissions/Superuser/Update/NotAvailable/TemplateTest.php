<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Update\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * - The target template is not legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["architect"],
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "updateCity"],
      "The update field should not be available for superuser if the target template is not legal."
    );
  }
}
