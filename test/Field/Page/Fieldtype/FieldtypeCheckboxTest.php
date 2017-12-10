<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;

class FieldtypeCheckboxTest extends GraphQLTestCase {

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = ['skyscraper'];
    Utils::module()->legalFields = ['featured'];
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    Utils::session()->logout();
  }

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