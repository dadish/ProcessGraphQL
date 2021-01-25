<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Trash;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class AvailableTest extends GraphqlTestCase
{
  /**
   * + For Superuser
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["skyscraper"],
    ];
  }

  public function testPermission()
  {
    assertTypePathExists(["Mutation", "trash"]);
  }
}
