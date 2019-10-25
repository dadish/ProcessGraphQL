<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNotAvailableParentTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template is legal.
   * + All the required fields are legal.
   * - But the configured parent template is not legal.
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['title'],
  ];

  public function testPermission() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNull($createSkyscraper, 'Create field should not be available if configured parent template is not legal.');
  }
}