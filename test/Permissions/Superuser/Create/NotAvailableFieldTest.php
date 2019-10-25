<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNnotAvailableFieldTest extends GraphqlTestCase {

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

  public function testSuperuserCanView() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNull($createSkyscraper, 'Create field is available.');
  }
}