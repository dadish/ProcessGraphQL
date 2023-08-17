<?php

namespace ProcessWire\GraphQL\Test\Field\FieldtypePageArrayFirst;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageArrayFirstTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["city"],
    "legalFields" => ["title"],
  ];

  public function testValue()
  {
    $firstCity = Utils::pages()
      ->find("template=city")
      ->first();
    $query = "{
  		city {
  			first {
          name
          id
          title
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $firstCity->name,
      $res->data->city->first->name,
      "Retrieves correct name of the first page."
    );
    self::assertEquals(
      $firstCity->id,
      $res->data->city->first->id,
      "Retrieves correct id of the first page."
    );
    self::assertEquals(
      $firstCity->title,
      $res->data->city->first->title,
      "Retrieves correct title of the first page."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
