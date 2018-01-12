<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphqlTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldAccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeIntegerTest extends GraphqlTestCase {  

  const TEMPLATE_NAME = 'skyscraper';
  const FIELD_NAME = 'floors';
  const FIELD_TYPE = 'FieldtypeInteger';

  use FieldtypeTestTrait;
  use FieldAccessTrait;

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
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->floors, $res->data->skyscraper->list[0]->floors, 'Retrieves field value.');
  }

}