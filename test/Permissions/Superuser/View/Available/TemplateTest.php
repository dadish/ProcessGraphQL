<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserViewAllowedTemplateTest extends GraphqlTestCase {

  /**
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin',
      'legalTemplates' => ['skyscraper'],
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