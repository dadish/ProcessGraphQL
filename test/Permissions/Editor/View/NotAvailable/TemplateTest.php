<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class EditorViewNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + For Editor.
   * + The target template is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['architect', 'city'], // <-- the skyscraper template is not legal
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    assertSchemaFieldNotExists(
      ['query', 'skyscraper'],
      'skyscraper field should not be available if skyscraper template is not legal.'
    );
  }
}