<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Create\NotAvailable;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class NoParentsTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The template is legal.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * - But template has noParents checked.
   */
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city", "skyscraper"],
    "legalFields" => ["title"],
    "access" => [
      "templates" => [
        [
          "name" => "skyscraper",
          "noParents" => 1,
        ],
      ],
    ],
  ];

  public function testPermission()
  {
    assertTypePathNotExists(
      ["Mutation", "createSkyscraper"],
      'createSkyscraper mutation field should not be available if the target template has "noParents" checked.'
    );
  }
}
