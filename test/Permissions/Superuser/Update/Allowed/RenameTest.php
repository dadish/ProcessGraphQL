<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserRenameTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The new name does not conflict.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['architect'],
    ];
  }

  public function testPermission() {
    $architect = Utils::pages()->get("template=architect, sort=random");
    $query = 'mutation renamePage($id: ID!, $page: ArchitectUpdateInput!){
      updateArchitect(id: $id, page: $page) {
        name
      }
    }';

    $newName = 'new-architect-name';

    $variables = [
      'id' => $architect->id,
      'page' => [
        'name' => $newName
      ]
    ];

    assertNotEquals($newName, $architect->name);
    $res = self::execute($query, $variables);
    assertEquals($res->data->updateArchitect->name, $newName, 'Allows to updates the name if it does not conflict.');
    assertEquals($newName, $architect->name, 'Updates the name of the target.');
  }
}