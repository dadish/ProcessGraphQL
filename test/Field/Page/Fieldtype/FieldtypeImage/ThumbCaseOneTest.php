<?php

/**
 * Superuser can view and create thumb via `size` field.
 * No need for explicit access settings.
 */

namespace ProcessWire\GraphQL\Test\FieldtypeImage;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class ThumbCaseOneTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["skyscraper"],
    "legalFields" => ["images"],
    "legalPageImageFields" => ["size"],
    "legalPageFileFields" => ["url"],
  ];

  // page that is used for this test case solely
  const PAGE_ID = 4182;

  public function testThumbCreate()
  {
    // get the test page
    $skyscraper = Utils::pages()->get("id=" . self::PAGE_ID);

    // get image from the images field
    $image = $skyscraper->images->first();

    // our thumb dimensions
    $thumbWidth = 400;
    $thumbHeight = 300;

    // make sure the thumbnail does not exist before we create it
    self::assertEquals(
      0,
      $image->getVariations()->count,
      "No thumbnail prior to test."
    );

    // build graphql query
    $query = "{
      skyscraper (s: \"id=$skyscraper->id\") {
        list {
          images {
            size (width: $thumbWidth, height: $thumbHeight) {
              width
              height
              url
            }
          }
        }
      }
    }";

    // execute graphql
    $res = self::execute($query);
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");

    // the thumb created by graphql
    $actualThumb = $res->data->skyscraper->list[0]->images[0]->size;

    // the created thumb's filename
    $filename = realpath($GLOBALS["pwDir"] . $actualThumb->url);

    // make sure it created the thumbnail
    self::assertTrue(file_exists($filename), "Admin creates the thumbnail.");
    self::assertTrue(is_file($filename), "Created thumbnail is a file.");

    // expected thumb
    $expectedThumb = $image->size($thumbWidth, $thumbHeight);

    // make sure it created the correct thumbnail
    self::assertEquals(
      $expectedThumb->url,
      $actualThumb->url,
      "Correct url for created thumbnail."
    );
    self::assertEquals(
      $expectedThumb->width,
      $actualThumb->width,
      "Correct width for created thumbnail."
    );
    self::assertEquals(
      $expectedThumb->height,
      $actualThumb->height,
      "Correct height for created thumbnail."
    );

    // clean up after test
    unlink($filename);
  }
}
