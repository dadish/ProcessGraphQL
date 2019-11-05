<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class SuperuserTrashNotAllowedTemplateTest extends GraphqlTestCase {

  /**
   * + For Superuser
   * + The targett template is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['city'],
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
    assertEquals(1, count($res->errors), 'Errors without trashing the page.');
    assertStringContainsString('trash', $res->errors[0]->message);
    assertFalse($skyscraper->isTrash(), 'Does not trashes the target page.');
  }
}