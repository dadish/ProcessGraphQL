<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCanViewFieldTest extends GraphqlTestCase {

  public static function getSettings()
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
            'roles' => [$editorRole->id],
          ]
        ],
        'fields' => [
          [
            'name' => 'height',
            'viewRoles' => [$editorRole->id],
          ]
        ]
      ]
    ];
  }

  public function testEditorCanViewField() {
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
      $target->id,
      $res->data->skyscraper->list[0]->id,
      'Retrieves correct id.'
    );
    $this->assertEquals(
      $target->height,
      $res->data->skyscraper->list[0]->height,
      'Editor can  view the height field if it has explicit access to it.'
    );
  }
}