<?php

/**
 * When user got access to requested page template but not
 * to the parents' template. The `parents` field returns
 * empty list.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageParentsFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['parents'],
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
    $this->assertEquals(0, count($res->data->skyscraper->list[0]->parents->list), 'Returns empty list when no access to parent pages template.');
  }

}