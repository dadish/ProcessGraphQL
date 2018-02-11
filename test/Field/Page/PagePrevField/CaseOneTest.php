<?php

/**
 * If user has access to prev sibling template then
 * `prev` returns a Page type.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PagePrevFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['prev', 'name'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          prev {
            name
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->prev->name, $res->data->skyscraper->list[0]->prev->name, 'Retrieves `prev` field of the page.');
  }

}