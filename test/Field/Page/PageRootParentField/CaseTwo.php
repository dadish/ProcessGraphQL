<?php

/**
 * If user do not have access to rootParent page template then
 * `rootParent` returns null page type.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageRootParentFieldCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['rootParent', 'name'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
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
    $this->assertEquals('', $res->data->skyscraper->list[0]->rootParent->name, '`rootParent` is null page type if there no access.');
  }

}