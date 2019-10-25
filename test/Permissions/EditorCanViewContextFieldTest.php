<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCanViewContextFieldTest extends GraphqlTestCase {

  public static function getSettings()
  {
    $editorRole = Utils::roles()->get('editor');
    $superuserRole = Utils::roles()->get('superuser');
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
            'viewRoles' => [$superuserRole->id],
          ],
          [
            'name' => 'height',
            'context' => 'skyscraper',
            'viewRoles' => [$editorRole->id],
          ]
        ]
      ]
    ];
  }

  public function testEditorCanViewContextField() {
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
      'Editor can  view the height field if it has explicit access to it in template context.'
    );
  }
}