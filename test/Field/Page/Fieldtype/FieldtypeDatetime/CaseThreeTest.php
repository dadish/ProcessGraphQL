<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

/**
 * It accepts format argument and correctly preformats it for the output.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeDatetimeCaseThreeTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['architect'],
    'legalFields' => ['born'],
  ];
  const FIELD_NAME = 'born';
  const FIELD_TYPE = 'FieldtypeDatetime';

  use FieldtypeTestTrait;
  use AccessTrait;

  public function testValue()
  {

    $architect = Utils::pages()->get("template=architect");
    $format = 'j/F/Y H-i-s';
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born (format: \"$format\")
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals(
      date($format, $architect->getUnformatted('born')),
      $res->data->architect->list[0]->born,
      'Formats datetime value correctly via format argument.'
    );
  }

}