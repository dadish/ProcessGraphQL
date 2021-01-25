<?php namespace ProcessWire\GraphQL\Test\Permissions\Editor\View\Available;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For Editor.
   * + The template is legal.
   * + The user got view permission on the template.
   */
  public static function getSettings()
  {
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => ["editor"], // <-- has view permission on skyscraper template.
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
          id
          name
          url
        }
      }
    }';

    $res = self::execute($query);
    self::assertNotNull(
      $res->data->skyscraper->list,
      "Should list the skyscraper pages."
    );
    self::assertNotNull($res->data->skyscraper->list[0]->id);
    self::assertNotNull($res->data->skyscraper->list[0]->name);
    self::assertNotNull($res->data->skyscraper->list[0]->url);
  }
}
