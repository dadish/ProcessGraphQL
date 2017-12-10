<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Field\Page\Fieldtype\FieldtypeDatetime;

class FieldtypeDatetimeTest extends GraphQLTestCase {

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = ['architect'];
    Utils::module()->legalFields = ['born'];
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    Utils::session()->logout();
  }

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born
        }
      }
    }";
    $res = $this->execute($query);
    $expected = date(FieldtypeDatetime::$format, $architect->born);
    $actual = $res->data->architect->list[0]->born;
    $this->assertEquals($expected, $actual, 'Retrieves datetime value.');
  }

}