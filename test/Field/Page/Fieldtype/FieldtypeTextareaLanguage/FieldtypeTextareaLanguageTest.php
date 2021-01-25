<?php

namespace ProcessWire\GraphQL\Test\FieldtypeTextareLanguage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeTextareaLanguageTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["basic-page"],
    "legalFields" => ["address"],
  ];
  const FIELD_NAME = "address";
  const FIELD_TYPE = "FieldtypeTextareaLanguage";

  use FieldtypeTestTrait;

  public function testValue()
  {
    $page = Utils::pages()->get("template=basic-page, address!=''");
    $query = "{
  		basicPage (s: \"id=$page->id\") {
  			list {
  				address
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      $page->address,
      $res->data->basicPage->list[0]->address,
      "Retrieves address value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }

  public function testLanguageValue()
  {
    $page = Utils::pages()->get("template=basic-page, address!=''");
    $query = 'query getBasicPage($s: Selector!){
      language (name: "ru")
  		basicPage (s: $s) {
  			list {
  				address
  			}
  		}
    }';
    $variable = [
      "s" => "id=$page->id",
    ];
    $res = self::execute($query, $variable);
    self::assertEquals(
      $page->getLanguageValue("ru", "address"),
      $res->data->basicPage->list[0]->address,
      "Retrieves address language value."
    );
    self::assertObjectNotHasAttribute("errors", $res, "There are errors.");
  }
}
