<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\ProcessWire;

class EditorViewAllowedTemplateTest extends GraphqlTestCase {

  /**
   * + For Editor.
   * + The template is legal.
   * + The user got view permission on the template.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'], // <-- has view permission on skyscraper template.
          ]
        ]
      ]
    ];
  }

  public function testPermission() {
    $query = '{
      skyscraper{
        list{
          id
          name
          url
        }
      }
    }';

    $res = self::execute($query);
    assertNotNull($res->data->skyscraper->list, 'Should list the skyscraper pages.');
    assertNotNull($res->data->skyscraper->list[0]->id);
    assertNotNull($res->data->skyscraper->list[0]->name);
    assertNotNull($res->data->skyscraper->list[0]->url);
  }
}