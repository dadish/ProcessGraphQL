<?php

/**
 * `parent` field selector respects access rules.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageParentFieldCaseFourTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['parent', 'name'],
  ];

  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parent (s: \"template=cities\") {
            name
          }
        }
      }
    }";
    $res = self::execute($query);
    $this->assertTrue(is_null($res->data->skyscraper->list[0]->parent), 'parent returns null if no access.');
  }

}