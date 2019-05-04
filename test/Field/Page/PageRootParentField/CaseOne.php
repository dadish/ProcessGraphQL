<?php

/**
 * If user has access to rootParent page template then
 * `rootParent` returns a Page type.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageRootParentFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper', 'cities'],
    'legalPageFields' => ['rootParent', 'name'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          rootParent {
            name
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->rootParent->name, $res->data->skyscraper->list[0]->rootParent->name, 'Retrieves `rootParent` field of the page.');
  }

}