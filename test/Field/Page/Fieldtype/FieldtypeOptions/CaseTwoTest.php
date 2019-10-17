<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypeOptionsCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['city'],
    'legalFields' => ['options_single'],
  ];

  
  public function testValue()
  {
    $city = Utils::pages()->get("template=city, options_single.count>0");
    $query = "{
      city (s: \"id=$city->id\") {
        list {
          options_single {
            title
            value
            id
          }
        }
      }
    }";
    $res = self::execute($query);
    $this->assertEquals($city->options_single->title, $res->data->city->list[0]->options_single->title, 'Retrieves correct option title.');
    $this->assertEquals($city->options_single->value, $res->data->city->list[0]->options_single->value, 'Retrieves correct option value.');
    $this->assertEquals($city->options_single->id, $res->data->city->list[0]->options_single->id, 'Retrieves correct option id.');
  }

}