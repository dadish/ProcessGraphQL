<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserTrashAllowedTest extends GraphqlTestCase {

  /**
   * + For Superuser
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['skyscraper'],
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = 'mutation trashPage($id: ID!) {
      trash(id: $id) {
        id
        name
      }
    }';
    $variables = [
      'id' => $skyscraper->id,
    ];

    assertFalse($skyscraper->isTrash());
    $res = self::execute($query, $variables);
    assertEquals($res->data->trash->id, $skyscraper->id, 'Trashes the page.');
    assertTrue($skyscraper->isTrash(), 'Trashes the correct page.');
  }
}