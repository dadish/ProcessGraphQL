<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class SuperuserMoveNameConflictTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The new parent template is legal.
   * - The new parent already has a child with the same name.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['city', 'skyscraper'],
      'legalPageFields' => ['parentID']
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
    $skyscraper->name = $futureSibling->name;
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