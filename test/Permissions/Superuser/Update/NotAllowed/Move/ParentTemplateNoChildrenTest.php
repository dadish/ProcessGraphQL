<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Update\NotAllowed\Move;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class ParentTemplateNoChildrenTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The target template is legal.
   * + The new parent template is legal.
   * - The parent template has noChildren checked.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["city", "skyscraper"],
      "access" => [
        "templates" => [
          [
            "name" => "city",
            "noChildren" => 1,
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
