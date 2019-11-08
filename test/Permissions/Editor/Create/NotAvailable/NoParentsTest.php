<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class EditorCreateNotAvailableNoParentsTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The template is legal.
   * + The configured parent template is legal.
   * + All the required fields are legal.
   * + Editor has all required permissions.
   * - Target template has noParents checked.
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
            'noParents' => 1,
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
      'createSkyscraper mutation field should not be available if the target template has "noParents" checked.'
    );
  }
}