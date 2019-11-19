<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorMoveParentTemplateNoChildrenTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The new parent template is legal.
   * + The user has all required permissions.
   * - The parent template has noChildren checked.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city', 'skyscraper'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-move']
            ]
          ],
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'addRoles' => ['editor'],
            'childTemplates' => ['skyscraper'],
            'noChildren' => 1 // <-- parent template has noChildren checked.
          ],
        ]
      ],
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        id
        name
      }
    }';


    $variables = [
      'page' => [
        'id' => $skyscraper->id,
        'parent' => $newParent->id,
      ]
    ];

    assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to move if new parent template has noChildren checked.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}