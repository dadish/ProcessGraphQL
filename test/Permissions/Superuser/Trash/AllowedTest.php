<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Trash;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class AllowedTest extends GraphqlTestCase
{
  /**
   * + For Superuser
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
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = 'mutation trashPage($id: ID!) {
      trash(id: $id) {
        id
        name
      }
    }';
    $variables = [
      "id" => $skyscraper->id,
    ];

    self::assertFalse($skyscraper->isTrash());
    $res = self::execute($query, $variables);
    self::assertEquals(
      $res->data->trash->id,
      $skyscraper->id,
      "Trashes the page."
    );
    self::assertTrue($skyscraper->isTrash(), "Trashes the correct page.");
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
