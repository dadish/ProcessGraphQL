<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeEmailTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['architect'],
    'legalFields' => ['email'],
  ];
  const FIELD_NAME = 'email';
  const FIELD_TYPE = 'FieldtypeEmail';

  use FieldtypeTestTrait;

  public function testValue()
  {
  	$architect = Utils::pages()->get("template=architect");
  	$query = "{
      architect (s: \"id=$architect->id\") {
        list {
          email
  			}
  		}
    }";
    $res = self::execute($query);
    assertEquals($architect->email, $res->data->architect->list[0]->email, 'Retrieves email value.');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}