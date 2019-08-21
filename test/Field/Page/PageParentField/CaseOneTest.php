<?php

/**
 * When user got access to both requested page template
 * and it's parent's template. The `parent` field returns
 * the parent page.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageParentFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city', 'skyscraper'],
    'legalPageFields' => ['parent', 'name'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          parent {
            name
          }
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals($skyscraper->parent->name, $res->data->skyscraper->list[0]->parent->name, 'Retrieves parent page.');
  }

}