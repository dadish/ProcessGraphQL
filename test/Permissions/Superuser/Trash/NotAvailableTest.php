<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class SuperuserTrashNotAvailableTest extends GraphqlTestCase {

  /**
   * + For Superuser
   * - There is no legal template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(['Mutation', 'trash']);
  }
}