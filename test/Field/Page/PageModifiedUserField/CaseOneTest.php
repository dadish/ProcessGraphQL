<?php

/**
 * If user has access to user template then
 * modifiedUser returns a user Page.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageModifiedUserFieldCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'user'],
    'legalPageFields' => ['modifiedUser'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				modifiedUser {
            name
          }
  			}
  		}
  	}";
  	$res = self::execute($query);
  	$this->assertEquals($skyscraper->modifiedUser->name, $res->data->skyscraper->list[0]->modifiedUser->name, 'Retrieves `modifiedUser` field of the page.');
  }

}