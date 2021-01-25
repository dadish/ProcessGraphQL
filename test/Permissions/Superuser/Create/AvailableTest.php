<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class AvailableTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The template should be legal.
   * + The configured parent template should be legal.
   * + All the required fields should be legal.
   */
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper", "city"],
    "legalFields" => ["title"],
  ];

  public function testPermission()
  {
    assertTypePathExists(
      ["Mutation", "createSkyscraper"],
      "createSKyscrpaer mutation field should be available."
    );
  }
}
