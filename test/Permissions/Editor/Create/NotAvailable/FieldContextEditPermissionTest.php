<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertSchemaFieldNotExists;

class EditorCreateNotAvailableFieldContextEditPermissionTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The template is legal.
   * + The configured parent template is legal.
   * + All required fields are legal.
   * + Editor has all required permissions for templates.
   * - Editor has edit permission on required field. (title)
   * - Editor's edit permission on required field is revoked on context level.
   */
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");

    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper', 'city'], 
      'legalFields' => ['title'], 
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
          ],
          [
            'name' => 'title',
            'context' => 'skyscraper',
            'editRoles' => [], // <-- the edit permission is revoked in the skyscraper template context
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