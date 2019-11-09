<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class EditorTrashNotAvailableTest extends GraphqlTestCase {

  /**
   * + For Superuser
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(['Mutation', 'trash']);
  }
}