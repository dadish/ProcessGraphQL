<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class SuperuserRenameNotAllowedTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * - The new name is already taken.
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
    $newName = Utils::pages()->get("template=architect, sort=random, id!={$architect->id}")->name;
    $query = 'mutation renamePage($id: ID!, $page: ArchitectUpdateInput!){
      updateArchitect(id: $id, page: $page) {
        name
      }
    }';

    $variables = [
      'id' => $architect->id,
      'page' => [
        'name' => $newName
      ]
    ];

    assertNotEquals($newName, $architect->name);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to updates the name if it conflicts.');
    assertStringContainsString($newName, $res->errors[0]->message);
    assertNotEquals($newName, $architect->name, 'Does not update the name of the target.');
  }
}