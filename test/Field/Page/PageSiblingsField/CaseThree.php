<?php

/**
 * `siblings` field supports optional selectors.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageSiblingsFieldCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['cities', 'architects', 'search', 'list-all'],
    'legalPageFields' => ['siblings'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $cities = Utils::pages()->get("template=cities");
    $query = "{
      cities (s: \"id=$cities->id\") {
        list {
          siblings (s: \"template=architects|search\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = $this->execute($query);
    $siblings = $cities->siblings("template=architects|search");
    $this->assertEquals($siblings->count, count($res->data->cities->list[0]->siblings->list), 'Returns the correct number of pages.');
    $this->assertEquals($siblings[0]->name, $res->data->cities->list[0]->siblings->list[0]->name, 'Returns the correct page at 0.');
    $this->assertEquals($siblings[1]->name, $res->data->cities->list[0]->siblings->list[1]->name, 'Returns the correct page at 1.');
  }

}