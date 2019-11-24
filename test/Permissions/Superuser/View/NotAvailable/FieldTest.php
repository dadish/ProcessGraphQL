<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


class SuperuserViewNotAvailableFieldTest extends GraphqlTestCase {

  /**
   * + The template is legal.
   * - The title field is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['skyscraper'],
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(['Query', 'skyscraper', 'list', 'title']);
  }
}