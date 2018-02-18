<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeOptionsCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['list-all'],
    'legalFields' => ['options_single'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $list_all = Utils::pages()->get("template=list-all");
    $query = "{
      list_all () {
        list {
          options_single {
            title
            value
            id
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($list_all->options_single->title, $res->data->list_all->list[0]->options_single->title, 'Retrieves correct option title.');
    $this->assertEquals($list_all->options_single->value, $res->data->list_all->list[0]->options_single->value, 'Retrieves correct option value.');
    $this->assertEquals($list_all->options_single->id, $res->data->list_all->list[0]->options_single->id, 'Retrieves correct option id.');
  }

}