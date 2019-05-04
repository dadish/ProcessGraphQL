<?php

/**
 * When user got access to both requested page template
 * and it's siblings' template. The `siblings` field returns
 * the siblings pages.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageSiblingsFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['siblings'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				siblings {
            list {
              name
            }
          }
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $this->assertEquals($skyscraper->siblings->count, count($res->data->skyscraper->list[0]->siblings->list), 'Retrieves correct number of siblings pages.');
    $this->assertEquals($skyscraper->siblings[0]->name, $res->data->skyscraper->list[0]->siblings->list[0]->name, 'Retrieves correct sibling page at 0.');
  	$this->assertEquals($skyscraper->siblings[1]->name, $res->data->skyscraper->list[0]->siblings->list[1]->name, 'Retrieves correct sibling page at 1.');
  }

}