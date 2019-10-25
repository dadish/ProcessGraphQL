<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCanNotViewTest extends GraphqlTestCase {

  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
    ];
  }

  public function testEditorCanNotView() {
    $target = Utils::pages()->get('template=skyscraper, sort=random');
    $query = "{
      skyscraper(s: \"id={$target->id}\") {
        list {
          id
          name
          url
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(
      1,
      count($res->errors),
      'Editor cannot view the skyscraper template if it does not have explicit access to it.'
    );
    $this->assertStringContainsString('skyscraper', $res->errors[0]->message);
  }
}