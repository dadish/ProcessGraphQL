<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorMoveParentTemplateTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * - The new parent template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    
    $query = 'mutation movePage($id: ID!, $page: SkyscraperUpdateInput!){
      updateSkyscraper(id: $id, page: $page) {
        id
        name
      }
    }';


    $variables = [
      'id' => $skyscraper->id,
      'page' => [
        'parent' => $newParent->id,
      ]
    ];

    assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to move if new parent template is not legal.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}