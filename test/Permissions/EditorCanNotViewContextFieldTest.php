<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCanNotViewContextFieldTest extends GraphqlTestCase {

  public static function getAccessRules()
  {
    $editorRole = Utils::roles()->get('editor');
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'legalFields' => ['height'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'view' => [$editorRole->id],
          ]
        ],
        'fields' => [
          [
            'name' => 'height',
            'view' => [$editorRole->id],
          ],
          [
            'name' => 'height',
            'context' => 'skyscraper',
            'view' => [],
          ]
        ]
      ]
    ];
  }

  public function testEditorCanNotViewContextField() {
    $target = Utils::pages()->get('template=skyscraper, sort=random');
    $query = "{
      skyscraper(s: \"id={$target->id}\") {
        list {
          id
          name
          height
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(
      1,
      count($res->errors),
      'Editor cannot view field if it is restricted in the context level.'
    );
    $this->assertStringContainsString(
      'height',
      $res->errors[0]->message
    );
  }
}