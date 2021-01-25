<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * - But the target template is not legal
   */
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["title"],
  ];

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "createSkyscraper"],
      "Create field should not be available if target template is not legal."
    );
  }
}
