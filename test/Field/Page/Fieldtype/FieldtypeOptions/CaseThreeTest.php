<?php

/**
 * You can pass a plain string for options field that
 * stores single option
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype\FieldtypeOptions;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\NullPage;

class FieldtypeOptionsCaseThreeTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['cities', 'city'],
    'legalFields' => ['options_single', 'title'],
  ];

	
  public function testValue()
  {
    $name = "new-city";
    $title = "New City";
    $option = 'Mon';
  	$query = 'mutation createPage ($page: CityCreateInput!) {
  		createCity (page: $page) {
  			name
  			id
        title
        options_single {
          title
          value
          id
        }
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => "4049",
				"name" => $name,
				"title" => $title,
        "options_single" => $option,
  		]
  	];
    $res = self::execute($query, $variables);

    $newCity = Utils::pages()->get("template=city, name=$name");
    $this->assertTrue(!$newCity instanceof NullPage, 'New Page is created.');
    $this->assertEquals($name, $newCity->name, 'New Page has correct name.');
    $this->assertEquals($title, $newCity->title, 'New Page has correct title.');
    $this->assertEquals($option, $newCity->options_single->title, 'New Page has correct option title.');
  }

}