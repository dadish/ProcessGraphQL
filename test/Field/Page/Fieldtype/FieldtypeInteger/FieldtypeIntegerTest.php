<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphqlTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeIntegerTest extends GraphqlTestCase {  

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['floors'],
  ];
  const FIELD_NAME = 'floors';
  const FIELD_TYPE = 'FieldtypeInteger';

  use FieldtypeTestTrait;

  public function testValue()
  {
    $skyscraper = Utils::pages()->find("template=skyscraper, sort=random")->first();
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          floors
        }
      }
    }";
    $res = self::execute($query);
    assertEquals($skyscraper->floors, $res->data->skyscraper->list[0]->floors, 'Retrieves field value.');
  }

}