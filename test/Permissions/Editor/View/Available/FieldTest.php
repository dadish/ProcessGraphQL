<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\Available;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["title"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"],
          ],
        ],
        "fields" => [
          [
            "name" => "title",
            "viewRoles" => ["editor"], // <-- has view permission for title!
          ],
        ],
      ],
    ];
  }

  public function testPermission()
  {
    $query = '{
      skyscraper{
        list{
          title
        }
      }
    }';

    $res = self::execute($query);
    self::assertNotNull(
      $res->data->skyscraper->list[0]->title,
      "Should show title field if it is legal."
    );
  }
}
