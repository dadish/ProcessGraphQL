<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNnotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * - But the template is not legal
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city'],
    'legalFields' => ['title'],
  ];

  public function testSuperuserCanView() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNull($createSkyscraper, 'Create field is available.');
  }
}