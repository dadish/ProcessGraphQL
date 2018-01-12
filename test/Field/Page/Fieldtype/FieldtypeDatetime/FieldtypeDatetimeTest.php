<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldAccessTrait;
use \ProcessWire\GraphQL\Field\Page\Fieldtype\FieldtypeDatetime;

class FieldtypeDatetimeTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'architect';
  const FIELD_NAME = 'born';
  const FIELD_TYPE = 'FieldtypeDatetime';

  use FieldtypeTestTrait;
  use FieldAccessTrait;

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