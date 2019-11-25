<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

/**
 * It returns correct output when custom dateOutputFormat
 * is set.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeDatetimeCaseTwoTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['architect'],
    'legalFields' => ['born'],
  ];
  const FIELD_NAME = 'born';
  const FIELD_TYPE = 'FieldtypeDatetime';

  use FieldtypeTestTrait;

  public function testValue()
  {
    // set output format for born (Datetime) field
    Utils::fields()->get('born')->dateOutputFormat = 'j F Y H:i:s';

    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born
        }
      }
    }";
    $res = self::execute($query);

    assertTrue($architect->outputFormatting(), 'Output formatting is on.');
    assertEquals(
      $architect->born,
      $res->data->architect->list[0]->born,
      'Retrieves correctly formatted datetime value.'
    );
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}