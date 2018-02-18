<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeOptionsCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['home'],
    'legalFields' => ['options'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $home = Utils::pages()->get("template=home");
    $query = "{
      home () {
        list {
          options {
            title
            value
            id
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($home->options->eq(0)->title, $res->data->home->list[0]->options[0]->title, 'Retrieves correct option title at 0.');
    $this->assertEquals($home->options->eq(1)->value, $res->data->home->list[0]->options[1]->value, 'Retrieves correct option value at 1.');
    $this->assertEquals($home->options->eq(2)->id, $res->data->home->list[0]->options[2]->id, 'Retrieves correct option id at 2.');
  }

}