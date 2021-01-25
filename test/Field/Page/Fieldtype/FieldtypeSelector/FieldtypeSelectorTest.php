<?php

namespace ProcessWire\GraphQL\Test\FieldtypeSelector;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeSelectorTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["home"],
    "legalFields" => ["selected"],
  ];
  const FIELD_NAME = "selected";
  const FIELD_TYPE = "FieldtypeSelector";

  use FieldtypeTestTrait;

  public function testValue()
  {
    $home = Utils::pages()->get("template=home");
    $query = "{
  		home (s: \"id=$home->id\") {
  			list {
  				selected
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $home->selected,
      $res->data->home->list[0]->selected,
      "Retrieves selector value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
