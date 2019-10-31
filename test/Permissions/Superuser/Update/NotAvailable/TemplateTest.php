<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class SuperuserUpdateNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['architect'],
    ];
  }

  public function testPermission() {
    assertSchemaFieldNotExists(
      ['mutation', 'updateCity'],
      'The update field should not be available for superuser if the target template is not legal.'
    );
  }
}