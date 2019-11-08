<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class EditorCreateNotAvailableFieldTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The template is legal.
   * + The configured parent template is legal.
   * + Editor has all required permissions.
   * - But one required fields is not legal. (title)
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");

    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper', 'city'], 
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => [$editorRole->id],
            'editRoles' => [$editorRole->id],
            'createRoles' => [$editorRole->id],
          ],
          [
            'name' => 'city',
            'roles' => [$editorRole->id],
            'addRoles' => [$editorRole->id],
          ]
        ],
        'fields' => [
          [
            'name' => 'title',
            'editRoles' => [$editorRole->id],
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(
      ['Mutation', 'createSkyscraper'],
      'createSkyscraper mutation field should not be available if one of the required fields is not legal.'
    );
  }
}