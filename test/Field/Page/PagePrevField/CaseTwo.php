<?php

/**
 * If user do not have access to prev sibling template then
 * `prev` returns null.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PagePrevFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['architects'],
    'legalPageFields' => ['prev'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$architects = Utils::pages()->get("template=architects");
  	$query = "{
  		architects (s: \"id=$architects->id\") {
  			list {
  				prev {
            name
          }
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $this->assertTrue(is_null($res->data->architects->list[0]->prev), '`prev` is null if there no access.');
  }

}