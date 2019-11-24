<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * If template skyscraper, architect and field architect is legal then
 * the architect page field should return list of architect pages.
 */

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypePageCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'architect'],
    'legalFields' => ['architects'],
  ];
  const FIELD_NAME = 'architects';
  const FIELD_TYPE = 'FieldtypePage';

  use FieldtypeTestTrait;

  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper, architects.count>1");
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
  	$res = self::execute($query);
  	assertEquals(
  		$skyscraper->architects->count,
  		count($res->data->skyscraper->list[0]->architects->list),
  		'Returns architect pages.'
  	);

    assertEquals(
      $skyscraper->architects[0]->id,
      $res->data->skyscraper->list[0]->architects->list[0]->id,
      'Returns correct first architect page.'
    );

    assertEquals(
      $skyscraper->architects[1]->id,
      $res->data->skyscraper->list[0]->architects->list[1]->id,
      'Returns correct second architect page.'
    );
  }

}