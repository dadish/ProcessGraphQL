<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * Returns the correctly formatted value.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageModifiedFieldCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['modified'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
    $format = 'Y-F-j H-i-s';
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				modified (format: \"$format\")
  			}
  		}
  	}";
  	$res = self::execute($query);
  	assertEquals(
      date($format, $skyscraper->modified),
      $res->data->skyscraper->list[0]->modified,
      'Retrieves correctly formatted value of `modified` field of the page.'
    );
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}