<?php

/**
 * `children` field supports optional selectors.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageChildrenFieldCaseThreeTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['home', 'cities', 'architects', 'search', 'list-all'],
    'legalPageFields' => ['children'],
  ];

  
  public function testValue()
  {
    $home = Utils::pages()->get("template=home");
    $query = "{
      home (s: \"id=$home->id\") {
        list {
          children (s: \"template=cities|architects\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $children = $home->children("template=cities|architects");
    assertEquals($children->count, count($res->data->home->list[0]->children->list), 'Returns the correct number of pages.');
    assertEquals($children[0]->name, $res->data->home->list[0]->children->list[0]->name, 'Returns the correct page at 0.');
    assertEquals($children[1]->name, $res->data->home->list[0]->children->list[1]->name, 'Returns the correct page at 0.');
  }

}