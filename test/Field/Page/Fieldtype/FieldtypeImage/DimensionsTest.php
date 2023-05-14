<?php

namespace ProcessWire\GraphQL\Test\FieldtypeImage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class DimensionsTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["images"],
  ];
  const FIELD_NAME = "images";
  const FIELD_TYPE = "FieldtypeImage";

  use FieldtypeTestTrait;

  public function testDimensions()
  {
    $skyscraper = Utils::pages()
      ->find("template=skyscraper, images.count>0, sort=random")
      ->first();
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          images {
            height
            width
          }
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      $skyscraper->images->first()->height,
      $res->data->skyscraper->list[0]->images[0]->height,
      "Retrieves image height."
    );
    self::assertEquals(
      $skyscraper->images->first()->width,
      $res->data->skyscraper->list[0]->images[0]->width,
      "Retrieves image width."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
