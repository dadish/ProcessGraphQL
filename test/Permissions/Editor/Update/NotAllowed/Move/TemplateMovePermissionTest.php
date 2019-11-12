<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorUpdateNotAllowedMovePermissionTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The target template is legal.
   * + The parent template is legal.
   * - The page-move permission is installed and user does not have that permission.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper', 'city'],
      'legalPageFields' => ['parentID'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              // 'editor' => ['page-move'] // <-- user should have page-move permission to move the page
            ]
          ],
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'addRoles' => ['editor'],
          ],
        ],
      ]
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    $query = 'mutation movePage($id: ID!, $page: SkyscraperUpdateInput!){
      updateSkyscraper(id: $id, page: $page) {
        id
        parentID
      }
    }';


    $variables = [
      'id' => $skyscraper->id,
      'page' => [
        'parent' => $newParent->id,
      ]
    ];

    assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to move the page if page-move permission is installed and user does not have it.');
    assertStringContainsString('move', $res->errors[0]->message);
  }
}