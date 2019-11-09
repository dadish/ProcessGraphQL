<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\Constraint\TypePathExists;
use ProcessWire\GraphQL\Test\GraphqlTestCase;

use function ProcessWire\GraphQL\Test\Assert\assertTypePathNotExists;

class EditorUpdateNotAvailableFieldTest extends GraphqlTestCase {

  /**
   * + For Editor.
   * + The tamplet is legal.
   * + The user has edit permission for the template.
   * - The field is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city'],
      'legalFields' => ['images'], // <-- the "title" field is not legal.
      'access' => [
        'templates' => [
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
          ],
        ],
        'fields' => [
          [
            'name' => 'title',
            'viewRoles' => ['editor'],
            'editRoles' => ['editor'],
          ],
          [
            'name' => 'images',
            'viewRoles' => ['editor'],
            'editRoles' => ['editor'],
          ],
        ]
      ]
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(
      ['CityUpdateInput', 'title'],
      'The "title" field for CityUpdateInput should not be available if the "title" field is not legal.'
    );
  }
}