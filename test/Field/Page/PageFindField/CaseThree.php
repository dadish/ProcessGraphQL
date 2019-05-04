<?php

/**
 * If user has only to couple necessary templates
 * then find returns a filtered list of pages that
 * user has access to.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageFindFieldCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['home', 'cities', 'architects'],
    'legalPageFields' => ['find'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$home = Utils::pages()->get("template=home");
  	$query = "{
  		home (s: \"id=$home->id\") {
  			list {
  				filtered: find (s: \"name!=''\") {
            getTotal
            list {
              name
            }
          }
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $filtered = Utils::pages()->get("template=home")->find("name!='', template=cities|architects|home");
    $this->assertEquals($filtered->count(), $res->data->home->list[0]->filtered->getTotal, 'Returns filtered list of pages.');
    $this->assertEquals($filtered->eq(1)->name, $res->data->home->list[0]->filtered->list[1]->name, 'Returns correct page at index 1.');
    $this->assertEquals($filtered->eq(2)->name, $res->data->home->list[0]->filtered->list[2]->name, 'Returns correct page at index 2.');
  }

}