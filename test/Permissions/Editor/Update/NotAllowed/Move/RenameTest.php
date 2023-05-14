<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAllowed\Move;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class RenameTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The target template is legal.
   * + The parent template is legal.
   * - The new name is already taken under the new parent.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["city", "skyscraper"],
      "access" => [
        "templates" => [
          [
            "name" => "city",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "addRoles" => ["editor"],
          ],
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "rolesPermissions" => [
              "editor" => ["page-move"],
            ],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get(
      "template=city, sort=random, id!={$skyscraper->parentID}"
    );
    $newName = Utils::pages()->get(
      "template=skyscraper, sort=random, parent=$newParent"
    )->name; // <-- the new name is already taken
    $query = 'mutation renamePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        name
      }
    }';

    $variables = [
      "page" => [
        "id" => $skyscraper->id,
        "name" => $newName,
        "parent" => (string) $newParent->id,
      ],
    ];

    self::assertNotEquals($newName, $skyscraper->name);
    self::assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    self::assertEquals(
      1,
      count($res->errors),
      "Does not allow to updates the name and parent if it conflicts."
    );
    assertStringContainsString($newName, $res->errors[0]->message);
    self::assertNotEquals(
      $newName,
      $skyscraper->name,
      "Does not update the name of the target."
    );

    // load the target skyscraper from db to make sure it was not updated
    $skyscraper = Utils::pages()
      ->find("id=$skyscraper", [
        "loadOptions" => [
          "getFromCache" => false,
        ],
      ])
      ->first();
    self::assertNotEquals(
      $newParent->id,
      $skyscraper->parentID,
      "Does not update the parent of the target."
    );
  }
}
