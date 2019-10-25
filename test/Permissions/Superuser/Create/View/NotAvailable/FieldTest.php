<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserViewNotAvailableFieldTest extends GraphqlTestCase {

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
          title
        }
      }
    }';

    $res = self::execute($query);
    $this->assertEquals(1, count($res->errors), '"skyscraper.title" field should not be available if it is not legal.');
    $this->assertStringContainsString('title', $res->errors[0]->message);
  }
}