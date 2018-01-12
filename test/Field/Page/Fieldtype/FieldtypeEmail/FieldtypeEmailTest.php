<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldAccessTrait;

class FieldtypeEmailTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'architect';
  const FIELD_NAME = 'email';
  const FIELD_TYPE = 'FieldtypeEmail';

  use FieldtypeTestTrait;
  use FieldAccessTrait;

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
  	$res = $this->execute($query);
  	$this->assertEquals($architect->email, $res->data->architect->list[0]->email, 'Retrieves email value.');
  }

}