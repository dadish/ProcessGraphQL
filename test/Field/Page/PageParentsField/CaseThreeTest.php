<?php

/**
 * `parents` field supports optional selector.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageParentsFieldCaseThreeTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'cities', 'home'],
    'legalPageFields' => ['parents', 'name'],
  ];

  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parents (s: \"template=cities|home\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $parents = $skyscraper->parents("template=cities|home");
    assertEquals($parents[0]->name, $res->data->skyscraper->list[0]->parents->list[0]->name, 'Retrieves correct parent page at 0.');
    assertEquals($parents[1]->name, $res->data->skyscraper->list[0]->parents->list[1]->name, 'Retrieves correct parent page at 1.');
    assertEquals($parents->count, count($res->data->skyscraper->list[0]->parents->list), 'Retrieves correct amount of parent pages.');
  }

}