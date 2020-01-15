<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

/**
 * Returns multiple values as expected.
 */
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypePageCaseFourTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper', 'architect'],
    'legalFields' => ['architects'],
  ];

  public function testValue()
  {
  	$query = 'query getSkyscrapers($s: Selector!){
  		skyscraper (s: $s) {
  			list {
  				architects {
  					list {
							id,
  					}
  				}
  			}
  		}
    }';
    $variables = [
      's' => "architects.count>4, limit=5"
    ];
    $res = self::execute($query, $variables);
  	assertGreaterThan(
  		1,
  		count($res->data->skyscraper->list[0]->architects->list),
  		'Returns empty list.'
  	);
	}
}