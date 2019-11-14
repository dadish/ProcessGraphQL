<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorTrashAllowedEditTrashCreatedTest extends GraphqlTestCase {

  /**
   * + For editor
   * + The template is legal.
   * + The user has page-edit-trash-created permission
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
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-edit-trash-created'] // <-- has page-edit-trash-created permission
            ]
          ]
        ],
      ]
    ];
  }

  private static $target = null;

  public static function setUpBeforeClass()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $skyscraper->template->allowChangeUser = 1;
    $skyscraper->of(false);
    $skyscraper->created_users_id = Utils::users()->get('editor')->id;
    $skyscraper->save('created_users_id', ['quite' => true]);
    $skyscraper->template->allowChangeUser = 0;
    $skyscraper->of(true);
    
    $skyscraper = Utils::pages()->get("id={$skyscraper->id}");
    self::$target = $skyscraper;
    parent::setUpBeforeClass();
  }

  public function testPermission() {
    $skyscraper = self::$target;
    $query = 'mutation trashPage($id: ID!) {
      trash(id: $id) {
        id
        name
      }
    }';
    $variables = [
      'id' => $skyscraper->id,
    ];

    assertFalse($skyscraper->isTrash());
    $res = self::execute($query, $variables);
    assertEquals($res->data->trash->id, $skyscraper->id, 'Trashes the page.');
    assertTrue($skyscraper->isTrash(), 'Trashes the correct page.');
  }
}