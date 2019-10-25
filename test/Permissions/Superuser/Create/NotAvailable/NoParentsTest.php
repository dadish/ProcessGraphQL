<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNotAvailableNoParentTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template is legal.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * - But template has noParents checked.
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city', 'skyscraper'],
    'legalFields' => ['title'],
    'access' => [
      'templates' => [
        [
          'name' => 'skyscraper',
          'noParents' => 1,
        ]
      ]
    ]
  ];

  public function testPermission() {
    $res = self::execute(GraphqlTestCase::introspectionQuery);
    $mutation = self::selectByProperty($res->data->__schema->types, 'name', 'Mutation');
    $this->assertNotNull($mutation, 'Mutation is available.');
    $createSkyscraper = self::selectByProperty($mutation->fields, 'name', 'createSkyscraper');
    $this->assertNull($createSkyscraper, 'Create field is available.');
  }
}