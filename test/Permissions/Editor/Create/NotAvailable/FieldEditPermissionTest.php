<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class EditorCreateNotAvailableFieldEditPermissionTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The template is legal.
   * + The configured parent template is legal.
   * + All required fields are legal.
   * + Editor has all required permissions for templates.
   * - Editor does not have edit permission on required field. (title)
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");

    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper', 'city'], 
      'legalFields' => ['title', 'images'], 
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
            'name' => 'images',
            'editRoles' => [$editorRole->id],
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    assertSchemaFieldNotExists(
      ['mutation', 'createSkyscraper'],
      'createSkyscraper mutation field should not be available if one of the required fields is not legal.'
    );
  }
}