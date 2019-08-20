<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * Returns correct default value.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageCreatedFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['created'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				created
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->created, $res->data->skyscraper->list[0]->created, 'Retrieves correct default `created` value.');
  }

}