<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits;

use \ProcessWire\GraphQL\Utils;

trait FieldtypeTestTrait {

  public function testFieldtype()
  {
    $field = Utils::fields()->get("name=" . self::FIELD_NAME);
    $this->assertEquals(self::FIELD_TYPE, $field->type->className(), 'Tests correct fieldtype.');
  }

}