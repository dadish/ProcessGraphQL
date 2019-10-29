<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldExists;

class SuperuserCreateAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template should be legal.
   * + The configured parent template should be legal.
   * + All the required fields should be legal.
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'city'],
    'legalFields' => ['title'],
  ];

  public function testPermission() {
    assertSchemaFieldExists(
      ['mutation', 'createSkyscraper'],
      'createSKyscrpaer mutation field should be available.'
    );
  }
}