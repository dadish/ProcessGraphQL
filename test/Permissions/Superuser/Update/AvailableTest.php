<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathExists;

class SuperuserUpdateAvailableTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['city'],
    ];
  }

  public function testPermission() {
    assertTypePathExists(
      ['Mutation', 'updateCity'],
      'The update field should be available for superuser if the target template is legal.'
    );
  }
}