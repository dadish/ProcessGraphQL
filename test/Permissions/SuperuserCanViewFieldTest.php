<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class SuperuserCanViewFieldTest extends GraphqlTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['title'],
  ];

  public function testSuperuserCanViewField() {
    $target = Utils::pages()->get('template=skyscraper, sort=random');
    $query = "{
      skyscraper(s: \"id={$target->id}\") {
        list {
          id
          name
          title
        }
      }
    }";
    $res = self::execute($query);
    assertEquals(
      $target->id,
      $res->data->skyscraper->list[0]->id,
      'Retrieves the correct id.'
    );
    assertEquals(
      $target->title,
      $res->data->skyscraper->list[0]->title,
      'Superuser can view title field if it is legal.'
    );
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }
}