<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateAvailableTest extends GraphqlTestCase {

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

  public function testSuperuserCanView() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNotNull($createSkyscraper, 'Create field is available.');
  }
}