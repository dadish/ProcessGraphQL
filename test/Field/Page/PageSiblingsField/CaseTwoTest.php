<?php

/**
 * When user got access to requested page template but not
 * it's siblings' template. The `siblings` field returns
 * an empty list.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageSiblingsFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['cities'],
    'legalPageFields' => ['siblings'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $cities = Utils::pages()->get("template=cities");
    $query = "{
      cities (s: \"id=$cities->id\") {
        list {
          siblings {
            list {
              name
            }
          }
        }
      }
    }";
    $res = $this->execute($query);
    $siblings = $cities->siblings("template=cities"); // only cities template is legal
    $this->assertEquals($siblings->count, count($res->data->cities->list[0]->siblings->list), 'Returns empty list when has no access siblings pages.');
  }

}