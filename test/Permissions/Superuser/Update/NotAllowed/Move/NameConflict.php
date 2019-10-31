<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserMoveNameConflictTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The new name does not conflict.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['city', 'skyscraper'],
      'legalPageFields' => ['parentID']
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    $query = 'mutation movePage($id: ID!, $page: SkyscraperUpdateInput!){
      updateSkyscraper(id: $id, page: $page) {
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
    assertEquals($res->data->updateSkyscraper->parentID, $newParent->id, 'Allows to move the page if both target and parent templates are legal.');
    assertEquals($newParent->id, $skyscraper->parentID, 'Updates the parent of the target.');
  }
}