<?php namespace ProcessWire\GraphQL\Test\Permissions\Superuser\Trash\NotAllowed;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class TemplateTest extends GraphqlTestCase
{
  /**
   * + For Superuser
   * + The targett template is not legal.
   */
  public static function getSettings()
  {
    return [
      "login" => "admin",
      "legalTemplates" => ["city"],
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
      1,
      count($res->errors),
      "Errors without trashing the page."
    );
    assertStringContainsString("trash", $res->errors[0]->message);
    self::assertFalse(
      $skyscraper->isTrash(),
      "Does not trashes the target page."
    );
  }
}
