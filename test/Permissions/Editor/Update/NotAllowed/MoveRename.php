<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorMoveRenameNotAllowedTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The parent template is legal.
   * - The new name is already taken under the new parent.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city', 'skyscraper'],
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, sort=random, id!={$skyscraper->parentID}");
    $newName = Utils::pages()->get("template=skyscraper, sort=random, parent=$newParent")->name;
    $query = 'mutation renamePage($id: ID!, $page: SkyscraperUpdateInput!){
      updateSkyscraper(id: $id, page: $page) {
        name
      }
    }';

    $variables = [
      'id' => $skyscraper->id,
      'page' => [
        'name' => $newName,
        'parent' => $newParent->id,
      ]
    ];

    assertNotEquals($newName, $skyscraper->name);
    assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to updates the name and parent if it conflicts.');
    assertStringContainsString($newName, $res->errors[0]->message);
    assertNotEquals($newName, $skyscraper->name, 'Does not update the name of the target.');

    // load the target skyscraper from db to make sure it was not updated
    $skyscraper = Utils::pages()->find("id=$skyscraper", [
      'loadOptions' => [
        'getFromCache' => false
      ]
    ])->first();
    assertNotEquals($newParent->id, $skyscraper->parentID, 'Does not update the parent of the target.');
  }
}