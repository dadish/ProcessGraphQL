<?php

namespace ProcessWire\GraphQL\Test\FieldtypeTexLanguage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeTextLanguageTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["basic-page"],
    "legalFields" => ["creator"],
  ];
  const FIELD_NAME = "creator";
  const FIELD_TYPE = "FieldtypeTextLanguage";

  use FieldtypeTestTrait;

  public function testValue()
  {
    $page = Utils::pages()->get("template=basic-page, creator!=''");
    $query = "{
  		basicPage (s: \"id=$page->id\") {
  			list {
  				creator
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $page->creator,
      $res->data->basicPage->list[0]->creator,
      "Retrieves creator value."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }

  public function testLanguageValue()
  {
    $page = Utils::pages()->get("template=basic-page, creator!=''");
    $query = 'query getBasicPage($s: Selector!){
      language (name: "ru")
  		basicPage (s: $s) {
  			list {
  				creator
  			}
  		}
    }';
    $variables = [
      "s" => "id=$page->id",
    ];
    $res = self::execute($query, $variables);
    self::assertEquals(
      $page->getLanguageValue("ru", "creator"),
      $res->data->basicPage->list[0]->creator,
      "Retrieves creator language value."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
