<?php

/**
 * When user got access to both requested page template
 * and it's parent's template. The `parents` field returns
 * the parents.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageParentsFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper', 'city', 'cities', 'home'],
    'legalPageFields' => ['parents', 'name'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parents {
            getTotal
            list {
              name
            }
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->parents[0]->name, $res->data->skyscraper->list[0]->parents->list[0]->name, 'Retrieves correct parent page at 0.');
    $this->assertEquals($skyscraper->parents[1]->name, $res->data->skyscraper->list[0]->parents->list[1]->name, 'Retrieves correct parent page at 1.');
    $this->assertEquals($skyscraper->parents[2]->name, $res->data->skyscraper->list[0]->parents->list[2]->name, 'Retrieves correct parent page at 2.');
    $this->assertEquals($skyscraper->parents->count, $res->data->skyscraper->list[0]->parents->getTotal, 'Retrieves correct amount of parent pages.');
  }

}