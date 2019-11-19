<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorUpdateAllowedTest extends GraphqlTestCase {

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
    assertEquals($res->data->updateSkyscraper->title, $newTitle, 'Allows to update the page title if both template and field are legal.');
    assertEquals($newTitle, $skyscraper->title, 'Updates the title of the target.');
  }
}