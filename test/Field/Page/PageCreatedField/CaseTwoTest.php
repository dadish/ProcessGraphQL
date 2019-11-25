<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * Returns correctly formatted value.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageCreatedFieldCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['created'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
    $format = 'j F Y H/i/s';
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				created (format: \"$format\")
  			}
  		}
  	}";
  	$res = $this->execute($query);
  	assertEquals(
      date($format, $skyscraper->created),
      $res->data->skyscraper->list[0]->created,
      'Retrieves correctly formatted `created` value.'
    );
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}