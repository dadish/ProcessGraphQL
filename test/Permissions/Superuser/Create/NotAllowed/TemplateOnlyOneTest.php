<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserCreateNotAllowedTemplateOnlyOneTest extends GraphqlTestCase {

  /**
   * + The template can have only one Page.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['home', 'search'],
      'legalFields' => ['title'],
      'access' => [
        'templates' => [
          [
            'name' => 'search',
            'noParents' => -1,
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    $query = 'mutation createPage($page: SearchCreateInput!) {
      createSearch(page: $page) {
        id
        name
        title
        template
      }
    }';

    $variables = [
      'page' => [
        'parent' => 1,
        'name' => 'search',
        'title' => 'Search'
      ]
    ];

    $res = self::execute($query, $variables);
    $this->assertEquals(1, count($res->errors), 'Should not allow to create a page with OnlyOne checked if there is already a page with that template.');
    $this->assertStringContainsString('Only one', $res->errors[0]->message);
  }
}