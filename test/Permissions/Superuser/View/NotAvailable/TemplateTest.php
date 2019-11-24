<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


class SuperuserViewNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * - The target template is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['architect', 'city'],
      'legalFields' => ['images']
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(['Query', 'skyscraper']);
  }
}