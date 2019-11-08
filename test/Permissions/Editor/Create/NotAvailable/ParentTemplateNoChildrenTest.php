<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class EditorCreateNotAvailableParentTemplateNoChildrenTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The template should be legal.
   * + The configured parent template should be legal.
   * + All the required fields should be legal.
   * - Configured parent template has noChildren checked.
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
            'noChildren' => 1,
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
      'Create field should not be available if configured parent template has noChildren checked.'
    );
  }
}