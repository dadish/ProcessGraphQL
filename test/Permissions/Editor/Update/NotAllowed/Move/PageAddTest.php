<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAllowed\Move;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class PageAddTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The target template is legal.
   * + The parent template is legal.
   * - The user does not have page-add permission for parent template.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper", "city"],
      "legalPageFields" => ["parentID"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "rolesPermissions" => [
              "editor" => ["page-move"],
            ],
          ],
          [
            "name" => "city",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            // 'addRoles' => ['editor'], // <-- no page-add permission for parent template
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get(
      "template=city, id!={$skyscraper->parentID}, sort=random"
    );
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        id
        parentID
      }
    }';

    $variables = [
      "page" => [
        "id" => $skyscraper->id,
        "parent" => $newParent->id,
      ],
    ];

    self::assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    self::assertEquals(
      1,
      count($res->errors),
      "Does not allow to move the page if user has no page-add permission for target parent template."
    );
    assertStringContainsString("parent", $res->errors[0]->message);
  }
}
