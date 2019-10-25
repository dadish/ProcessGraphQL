<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;

class SuperuserViewNotAvailableTemplateTest extends GraphqlTestCase {

  /**
   * + The template is legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'admin'
    ];
  }

  public function testPermission() {
    $query = '{
      skyscraper{
        list{
          id
        }
      }
    }';

    $res = self::execute($query);
    $this->assertEquals(1, count($res->errors), '"skyscraper" field should not be available if it is not legal.');
    $this->assertStringContainsString('skyscraper', $res->errors[0]->message);
  }
}