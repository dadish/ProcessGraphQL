<?php

/**
 * When user got access to requested page template but not
 * it's childrens's template. The `children` field returns
 * an empty list.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageChildrenFieldCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city'],
    'legalPageFields' => ['children'],
  ];

  
  public function testValue()
  {
    $city = Utils::pages()->get("template=city");
    $query = "{
      city (s: \"id=$city->id\") {
        list {
          children {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(0, count($res->data->city->list[0]->children->list), 'Returns empty list when has no access children pages.');
  }

}