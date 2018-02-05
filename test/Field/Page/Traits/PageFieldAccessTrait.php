<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Traits;

use \ProcessWire\GraphQL\Utils;

trait PageFieldAccessTrait {

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    Utils::module()->legalTemplates = is_array(self::TEMPLATE_NAME) ? self::TEMPLATE_NAME : [self::TEMPLATE_NAME];
    Utils::module()->legalPageFields = array_merge(Utils::module()->legalPageFields, is_array(self::PAGE_FIELD_NAME) ? self::PAGE_FIELD_NAME : [self::PAGE_FIELD_NAME]);
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    Utils::session()->logout();
    parent::tearDownAfterClass();
  }

}