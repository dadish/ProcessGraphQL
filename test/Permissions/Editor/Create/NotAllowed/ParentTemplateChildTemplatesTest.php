<?php

namespace ProcessWire\GraphQL\Test\Permissions\Editor\Create\NotAllowed;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class ParentTemplateChildTemplatesTest extends GraphqlTestCase
{
  /**
   * + For Editor
   * + Everything in line for createSearch field.
   * - The target parent template has childTemplates without target template.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["home", "search"],
      "legalFields" => ["title"],
      "access" => [
        "templates" => [
          [
            "name" => "search",
            "noParents" => 0,
            "noChildren" => 0,
            "roles" => ["editor"],
            "editRoles" => ["editor"],
            "createRoles" => ["editor"],
          ],
          [
            "name" => "home",
            "roles" => ["editor"],
            "addRoles" => ["editor"],
            "noChildren" => 0,
            "childTemplates" => ["list-all", "cities"], // <-- has no 'search' template
          ],
        ],
        "fields" => [
          [
            "name" => "title",
            "viewRoles" => ["editor"],
            "editRoles" => ["editor"],
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    $query = 'mutation createPage($page: SearchCreateInput!) {
      createSearch(page: $page) {
        id
        name
        title
        template
      }
    }';

    $variables = [
      "page" => [
        "parent" => 1, // <-- setting a "home" as a parent.
        "name" => "search",
        "title" => "Search",
      ],
    ];

    $res = self::execute($query, $variables);
    self::assertEquals(
      1,
      count($res->errors),
      "Should not allow to create a page if parent template has childTemplates without target template."
    );
    assertStringContainsString("parent", $res->errors[0]->message);
  }
}
