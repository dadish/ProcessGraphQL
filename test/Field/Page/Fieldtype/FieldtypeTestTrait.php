<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Utils;

trait FieldtypeTestTrait {

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = [self::TEMPLATE_NAME];
    Utils::module()->legalFields = [self::FIELD_NAME];
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    Utils::session()->logout();
  }

  public function testType()
  {
    $field = Utils::fields()->get("name=" . self::FIELD_NAME);
    $this->assertEquals(self::FIELD_TYPE, $field->type->className(), 'Tests correct fieldtype.');
  }

}