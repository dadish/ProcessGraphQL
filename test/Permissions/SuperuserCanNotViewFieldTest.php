<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCanNotViewFieldTest extends GraphqlTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['height'],
  ];

  public function testSuperuserCanNotViewField() {
    $target = Utils::pages()->get('template=skyscraper, sort=random');
    $query = "{
      skyscraper(s: \"id={$target->id}\") {
        list {
          id
          name
          height
          title
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(
      1,
      count($res->errors),
      'Superuser can not view the title field if it is not legal.'
    );
    $this->assertStringContainsString('title', $res->errors[0]->message);
  }
}