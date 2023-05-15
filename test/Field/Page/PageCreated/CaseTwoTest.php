<?php

namespace ProcessWire\GraphQL\Test\FieldtypePageCreated;

/**
 * Returns correctly formatted value.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalPageFields" => ["created"],
  ];

  public function testValue()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper");
    $format = "j F Y H/i/s";
    $query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				created (format: \"$format\")
  			}
  		}
  	}";
    $res = $this->execute($query);
    self::assertEquals(
      date($format, $skyscraper->created),
      $res->data->skyscraper->list[0]->created,
      "Retrieves correctly formatted `created` value."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
