<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;


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
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper', 'city'], 
      'legalFields' => ['title', 'images'], 
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'createRoles' => ['editor'],
          ],
          [
            'name' => 'city',
            'roles' => ['editor'],
            'addRoles' => ['editor'],
          ]
        ],
        'fields' => [
          [
            'name' => 'images',
            'editRoles' => ['editor'],
          ],
          [
            'name' => 'title',
            // 'editRoles' => ['editor'], // <-- has no edit permission to the required "title" field.
          ],
        ]
      ]
    ];
  }

  public function testPermission() {
    assertTypePathNotExists(
      ['Mutation', 'createSkyscraper'],
      'createSkyscraper mutation field should not be available if user has no edit permission on the required field.'
    );
  }
}