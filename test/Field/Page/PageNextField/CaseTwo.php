<?php

/**
 * If user do not have access to next sibling template then
 * `next` returns null.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageNextFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['cities'],
    'legalPageFields' => ['next'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$cities = Utils::pages()->get("template=cities");
  	$query = "{
  		cities (s: \"id=$cities->id\") {
  			list {
  				next {
            name
          }
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $this->assertTrue(is_null($res->data->cities->list[0]->next), '`next` is null if there no access.');
  }

}