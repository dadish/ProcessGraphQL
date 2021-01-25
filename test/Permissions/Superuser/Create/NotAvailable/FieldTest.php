<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The template is legal.
   * + The configured parent template is legal.
   * - But one required fields is not legal. (title)
   */
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city", "skyscraper"],
  ];

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "createSkyscraper"],
      "createSkyscraper mutation field should not be available if one of the required fields is not legal."
    );
  }
}
