<?php

namespace ProcessWire\GraphQL\Test\FieldtypeArrayLast;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageArrayLastTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["title"],
  ];

  public function testValue()
  {
    $lastCity = Utils::pages()
      ->find("template=city, limit=50")
      ->last();
    $query = "{
  		city {
  			last {
          name
          id
          title
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $lastCity->name,
      $res->data->city->last->name,
      "Retrieves correct name of the last page."
    );
    self::assertEquals(
      $lastCity->id,
      $res->data->city->last->id,
      "Retrieves correct id of the last page."
    );
    self::assertEquals(
      $lastCity->title,
      $res->data->city->last->title,
      "Retrieves correct title of the last page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
