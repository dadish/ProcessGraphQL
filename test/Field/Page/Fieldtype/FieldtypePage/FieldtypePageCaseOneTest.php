<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * When template skyscraper and field architect is enabled but
 * template architect is not enabled. The architect page field
 * should return empty list.
 */

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypePageCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['architects'],
  ];
  const FIELD_NAME = 'architects';
  const FIELD_TYPE = 'FieldtypePage';

  use FieldtypeTestTrait;
  use AccessTrait;

  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper, architects.count>0");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				architects {
  					list {
  						id
  						name
  					}
  				}
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	$this->assertEquals(
  		0,
  		count($res->data->skyscraper->list[0]->architects->list),
  		'Returns empty list.'
  	);
  }

}