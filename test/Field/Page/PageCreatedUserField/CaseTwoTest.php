<?php

/**
 * If user do not have access to user template then
 * createdUser returns empty user object.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageCreatedUserFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['createdUser'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				createdUser {
            name
            email
            id
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    $this->assertEquals('', $res->data->skyscraper->list[0]->createdUser->name, '`createdUser->name` is empty string when no access.');
    $this->assertEquals('', $res->data->skyscraper->list[0]->createdUser->email, '`createdUser->email` is empty string when no access.');
  	$this->assertEquals('', $res->data->skyscraper->list[0]->createdUser->id, '`createdUser->id` is empty string when no access.');
  }

}