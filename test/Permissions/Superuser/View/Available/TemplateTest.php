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
    $this->assertNotNull($res->data->skyscraper->list, 'Should list the skyscraper pages.');
    $this->assertNotNull($res->data->skyscraper->list[0]->id);
    $this->assertNotNull($res->data->skyscraper->list[0]->name);
    $this->assertNotNull($res->data->skyscraper->list[0]->url);
  }
}