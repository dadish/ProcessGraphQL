<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAllowed;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class RenameTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The target template is legal.
   * + The user has all required permissions.
   * - The new name is already taken.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["architect"],
      "access" => [
        "templates" => [
          [
            "name" => "architect",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    $architect = Utils::pages()->get("template=architect, sort=random");
    $newName = Utils::pages()->get(
      "template=architect, sort=random, id!={$architect->id}"
    )->name;
    $query = 'mutation renamePage($page: ArchitectUpdateInput!){
      updateArchitect(page: $page) {
        name
      }
    }';

    $variables = [
      "page" => [
        "id" => $architect->id,
        "name" => $newName,
      ],
    ];

    self::assertNotEquals($newName, $architect->name);
    $res = self::execute($query, $variables);
    self::assertEquals(
      1,
      count($res->errors),
      "Does not allow to updates the name if it conflicts."
    );
    assertStringContainsString($newName, $res->errors[0]->message);
    self::assertNotEquals(
      $newName,
      $architect->name,
      "Does not update the name of the target."
    );
  }
}
