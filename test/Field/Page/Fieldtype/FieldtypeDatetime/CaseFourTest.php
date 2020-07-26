<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

/**
 * Accepts a string as an input value.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypeDatetimeCaseFourTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['architect'],
    'legalFields' => ['born'],
  ];

  public function testValue()
  {

    $architect = Utils::pages()->get("template=architect");
    $format = 'd/m/Y H:i:s';
    $query = 'mutation updatePage($page: ArchitectUpdateInput!){
      architect: updateArchitect(page: $page) {
          born
      }
    }';
  	$variables = [
  		"page" => [
				"id" => $architect->id,
				"born" => "01/02/2020 01:02:03"
  		],
  	];
    $res = self::execute($query, $variables);
    assertEquals(
      $architect->getUnformatted('born'),
      $res->data->architect->born,
      'Accepts string as an input value.'
    );
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}