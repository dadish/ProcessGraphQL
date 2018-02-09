<?php

/**
 * If user has access to next sibling template then
 * `next` returns a Page type.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageNextFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['next', 'name'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          next {
            name
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->next->name, $res->data->skyscraper->list[0]->next->name, 'Retrieves `next` field of the page.');
  }

}