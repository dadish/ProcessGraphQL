<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits;

use \ProcessWire\GraphQL\Utils;

trait FieldAccessTrait {

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

}