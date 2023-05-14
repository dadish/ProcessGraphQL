<?php

/**
 * When user is editor, `view` access is enabled to template and `view` & `edit`
 * access is enabled to field, the `size` field creates thumb image and returns it.
 */

namespace ProcessWire\GraphQL\Test\FieldtypeImage;

use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class ThumbCaseFourTest extends GraphQLTestCase
{
  const PAGE_ID = 4189;

  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["images"],
      "legalPageImageFields" => ["size"],
      "legalPageFileFields" => ["url"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => [$editorRole->id],
          ],
        ],
        "fields" => [
          [
            "name" => "images",
            "viewRoles" => [$editorRole->id],
            "editRoles" => [$editorRole->id],
          ],
        ],
      ],
    ];
  }

  public function testThumbCreate()
  {
    // make sure user is logged in as an editor
    self::assertEquals(
      Utils::user()->name,
      "editor",
      "Logged in as an editor."
    );
    self::assertTrue(
      Utils::user()->hasRole("editor"),
      "Editor has editor role."
    );

    // make sure editor has explicit rights to view the skyscraper pages
    self::assertTrue(
      Utils::templates()
        ->get("skyscraper")
        ->hasRole("editor", "view"),
      "skyscraper template has view access for editor role."
    );

    // make sure editor has explicit right to view and edit images field
    $editorRole = Utils::roles()->get("editor");
    $imagesField = Utils::fields()->get("images");
    self::assertTrue(
      in_array($editorRole->id, $imagesField->viewRoles),
      "images field has view access for editor role."
    );
    // self::assertTrue(
    //   in_array($editorRole->id, $imagesField->editRoles),
    //   'images field has edit access for editor role.'
    // );

    // get the test page
    $skyscraper = Utils::pages()->get("id=" . self::PAGE_ID);

    // get image from the images field
    $image = $skyscraper->images->first();

    // our thumb dimensions
    $thumbWidth = 445;
    $thumbHeight = 335;

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
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");

    // the thumb created by graphql
    $actualThumb = $res->data->skyscraper->list[0]->images[0]->size;

    // the created thumb's filename
    $filename = realpath($GLOBALS["pwDir"] . $actualThumb->url);

    // make sure it created the thumbnail
    self::assertTrue(file_exists($filename), "Editor creates the thumbnail.");
    self::assertTrue(is_file($filename), "The created thumbnail is a file.");

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
