<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Trash;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class NotAvailableTest extends GraphqlTestCase
{
  /**
   * + For Superuser
   * - There is no legal template.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
    ];
  }

  public function testPermission()
  {
    assertTypePathNotExists(["Mutation", "trash"]);
  }
}
