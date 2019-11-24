<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


class SuperuserCreateNotAvailableParentTemplateNoChildrenTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The template is legal.
   * + All the required fields are legal.
   * + The configured parent template is legal.
   * - But the configured parent template has noChildren checked.
   */
  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city', 'skyscraper'],
    'legalFields' => ['title'],
    'access' => [
      'templates' => [
        [
          'name' => 'city',
          'noChildren' => 1,
        ]
      ]
    ]
  ];

  public function testPermission() {
    assertTypePathNotExists(
      ['Mutation', 'createSkyscraper'],
      'Create field should not be available if configured parent template has noChildren checked.'
    );
  }
}