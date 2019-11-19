<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

use function ProcessWire\GraphQL\Test\Assert\assertStringContainsString;

class EditorUpdateNotAllowedEditCreatedPermissionTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + Field is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
          ]
        ],
        'fields' => [
          [
            'name' => 'title',
            'viewRoles' => ['editor'],
            'editRoles' => ['editor'],
          ]
        ],
        'roles' => [
          [
            'name' => 'editor',
            'add' => ['page-edit-created'] // <-- can only edit pages created by herself
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newTitle = 'New Title for Skyscraper';
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        id
        title
      }
    }';


    $variables = [
      'page' => [
        'id' => $skyscraper->id,
        'title' => $newTitle,
      ]
    ];

    assertNotEquals($newTitle, $skyscraper->title);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to update the page if user has page-edit-created permission and did not create the page.');
    assertStringContainsString('not allowed', $res->errors[0]->message);
  }
}