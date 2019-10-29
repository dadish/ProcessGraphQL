<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class SuperuserCreateNotAvailableFieldTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template is legal.
   * + The configured parent template is legal.
   * - But one required fields is not legal. (title)
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city', 'skyscraper'],
  ];

  public function testPermission() {
    assertSchemaFieldNotExists(
      ['mutation', 'createSkyscraper'],
      'createSkyscraper mutation field should not be available if one of the required fields is not legal.'
    );
  }
}