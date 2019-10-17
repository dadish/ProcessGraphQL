<?php

/**
 * `parents` field selector respects access rules.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageParentsFieldCaseFourTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'city'],
    'legalPageFields' => ['parents', 'name'],
  ];

  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parents (s: \"template=cities\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals(0, count($res->data->skyscraper->list[0]->parents->list), 'parents returns empty list if no access.');
  }

}