<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserUpdateAllowedTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + Field is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['skyscraper'],
      'legalFields' => ['title']
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newTitle = 'New Title for Skyscraper';
    $query = 'mutation movePage($id: ID!, $page: SkyscraperUpdateInput!){
      updateSkyscraper(id: $id, page: $page) {
        id
        title
      }
    }';


    $variables = [
      'id' => $skyscraper->id,
      'page' => [
        'title' => $newTitle,
      ]
    ];

    assertNotEquals($newTitle, $skyscraper->title);
    $res = self::execute($query, $variables); 
    assertEquals($res->data->updateSkyscraper->title, $newTitle, 'Allows to update the page title if both template and field are legal.');
    assertEquals($newTitle, $skyscraper->title, 'Updates the title of the target.');
  }
}