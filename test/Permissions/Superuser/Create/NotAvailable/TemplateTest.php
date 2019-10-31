<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class SuperuserCreateNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * - But the target template is not legal
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city'],
    'legalFields' => ['title'],
  ];

  public function testPermission() {
    assertSchemaFieldNotExists(
      ['mutation', 'createSkyscraper'],
      'Create field should not be available if target template is not legal.'
    );
  }
}