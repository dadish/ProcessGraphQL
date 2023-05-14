<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\View\Available;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["skyscraper"],
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
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
