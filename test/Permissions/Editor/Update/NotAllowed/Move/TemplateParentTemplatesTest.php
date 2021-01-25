<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Update\NotAllowed\Move;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class TemplateParentTemplatesTest extends GraphqlTestCase
{
  /**
   * + For editor.
   * + The target template is legal.
   * + The new parent template is legal.
   * + User has all required permissions.
   * - The new parent template does not match the target template's parentTemplates property.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["city", "skyscraper"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "rolesPermissions" => [
              "editor" => ["page-move"],
            ],
            "parentTemplates" => ["architects"], // <-- parent template "city" is not allowed
          ],
          [
            "name" => "city",
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "addRoles" => ["editor"],
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
        name
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
      "Does not allow to move if new parent template is not legal."
    );
    assertStringContainsString("parent", $res->errors[0]->message);
  }
}
