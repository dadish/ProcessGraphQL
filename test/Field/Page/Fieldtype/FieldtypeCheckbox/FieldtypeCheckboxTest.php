<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeCheckboxTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['featured'],
  ];
  const FIELD_NAME = 'featured';
  const FIELD_TYPE = 'FieldtypeCheckbox';

  use FieldtypeTestTrait;
  use AccessTrait;

  public function testTruthyValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, featured=0");
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          featured
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertFalse($res->data->skyscraper->list[0]->featured);
  }

  public function testFalsyValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, featured=1");
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          featured
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertTrue($res->data->skyscraper->list[0]->featured);
  }

}