<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use ProcessWire\GraphQL\Utils;

class SuperuserCanNotViewTest extends GraphqlTestCase {
  const accessRules = [
    'legalTemplates' => ['skyscraper'],
  ];

  use AccessTrait;

  public function testSuperuserCanNotView() {
    $target = Utils::pages()->get('template=architect, sort=random');
    $query = "{
      architect(s: \"id={$target->id}\") {
        list {
          id
          name
          url
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(1, count($res->errors));
    $this->assertStringContainsString('architect', $res->errors[0]->message);
  }
}