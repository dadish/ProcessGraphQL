<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\View\Available;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class FieldTest extends GraphqlTestCase
{
  /**
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["title"],
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
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
