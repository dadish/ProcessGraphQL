<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCreateNotAllowedTemplateParentTemplatesTest extends GraphqlTestCase {

  /**
   * + The target template is legal.
   * + The target parent template is legal.
   * + The target parent can have any child.
   * - The target template has parentTemplates without target parent.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['city', 'skyscraper'],
      'legalFields' => ['title'],
    ];
  }

  public function testPermission() {
    $architects = Utils::pages()->get("template=architects");
    $query = 'mutation createPage($page: SkyscraperCreateInput!) {
      createSkyscraper(page: $page) {
        id
        name
        title
        template
      }
    }';

    $variables = [
      'page' => [
        'parent' => $architects->id,
        'name' => 'search-2',
        'title' => 'Search 2'
      ]
    ];

    $res = self::execute($query, $variables);
    $this->assertEquals(1, count($res->errors), 'Should not allow to create a page under the page with template that is not in parentTemplates of the target template.');
    $this->assertStringContainsString('parent', $res->errors[0]->message);
  }
}