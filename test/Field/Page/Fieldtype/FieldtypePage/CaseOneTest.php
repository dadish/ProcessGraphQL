<?php

namespace ProcessWire\GraphQL\Test\FieldtypePage;

/**
 * When template skyscraper and field architect is enabled but
 * template architect is not enabled. The architect page field
 * should return empty list.
 */

use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use ProcessWire\GraphQL\Utils;

class CaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["architects"],
  ];
  const FIELD_NAME = "architects";
  const FIELD_TYPE = "FieldtypePage";

  use FieldtypeTestTrait;

  public function testValue()
  {
    $skyscraper = Utils::pages()->get(
      "template=skyscraper, architects.count>0"
    );
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				architects {
  					list {
  						id
  						name
  					}
  				}
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      0,
      count($res->data->skyscraper->list[0]->architects->list),
      "Returns empty list."
    );
  }

  public function testAnotherValue()
  {
    $skyscraper = Utils::pages()->get(
      "template=skyscraper, architects.count>0"
    );
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				architects {
  					list {
  						id
  					}
  					first {
  						id
  					}
  					last {
  						id
  					}
  				}
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      0,
      count($res->data->skyscraper->list[0]->architects->list),
      "Returns empty list."
    );
    self::assertNull(
      $res->data->skyscraper->list[0]->architects->first,
      "Returns empty first item."
    );
    self::assertNull(
      $res->data->skyscraper->list[0]->architects->last,
      "Returns empty last item."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
