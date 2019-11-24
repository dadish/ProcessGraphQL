<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;


class EditorNotAllowedMoveNameConflictTest extends GraphqlTestCase {

  /**
   * + For editor.
   * + The target template is legal.
   * + The new parent template is legal.
   * + User got all required permissions
   * - The new parent already has a child with the same name.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city', 'skyscraper'],
      'legalPageFields' => ['parentID'],
      'access' => [
        'templates' => [
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'addRoles' => ['editor'],
          ],
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-move']
            ]
          ]
        ]
      ]
    ];
  }

  private static $skyscraper = null;
  private static $originalName = '';

  public static function setUpBeforeClass()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    self::$skyscraper = $skyscraper;
    self::$originalName = $skyscraper->name;
    parent::setUpBeforeClass();
  }

  public static function tearDownAfterClass()
  {
    $skyscraper = self::$skyscraper;
    $skyscraper->of(true);
    $skyscraper->name = self::$originalName;
    $skyscraper->save();
    parent::tearDownAfterClass();
  }

  public function testPermission() {
    $skyscraper = self::$skyscraper;
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    $futureSibling = Utils::pages()->get("template=skyscraper, sort=random, parent={$newParent}");
    $skyscraper->of(true);
    $skyscraper->name = $futureSibling->name; // <-- name is the same as future sibling
    $skyscraper->save();
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        parentID
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
    assertEquals(1, count($res->errors), 'Does not allow to move if new parent already has a page with the same name.');
    assertStringContainsString('parent', $res->errors[0]->message);
    assertStringContainsString('name', $res->errors[0]->message);
  }
}