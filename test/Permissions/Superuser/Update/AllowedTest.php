<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Update;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class AllowedTest extends GraphqlTestCase
{
  /**
   * + For superuser.
   * + The target template is legal.
   * + Field is legal.
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
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newTitle = "New Title for Skyscraper";
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        id
        title
      }
    }';

    $variables = [
      "page" => [
        "id" => $skyscraper->id,
        "title" => $newTitle,
      ],
    ];

    self::assertNotEquals($newTitle, $skyscraper->title);
    $res = self::execute($query, $variables);
    self::assertEquals(
      $res->data->updateSkyscraper->title,
      $newTitle,
      "Allows to update the page title if both template and field are legal."
    );
    self::assertEquals(
      $newTitle,
      $skyscraper->title,
      "Updates the title of the target."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
