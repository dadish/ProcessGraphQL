<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


class SuperuserViewNotAvailableTemplateViewPermissionTest extends GraphqlTestCase {

  /**
   * + For Editor
   * + The target template is not legal.
   * - The user has no view permission for the target template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            // 'roles' => ['editor'], // <-- has no view permission
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(
      ['Query', 'skyscraper'],
      'skyscraper field should not be available if the user has no view permission for the targat template.'
    );
  }
}