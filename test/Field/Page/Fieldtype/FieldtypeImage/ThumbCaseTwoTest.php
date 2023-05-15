<?php

/**
 * When user is editor and `view` access is enabled to template and field,
 * the user can query an existing thumb via `size` field.
 */

namespace ProcessWire\GraphQL\Test\FieldtypeImage;

use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class ThumbCaseTwoTest extends GraphQLTestCase
{
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
          ],
        ],
      ],
    ];
  }

  const PAGE_ID = 4186;

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

    // make sure editor has explicit right to view images field
    $editorRole = Utils::roles()->get("editor");
    $imagesField = Utils::fields()->get("images");
    self::assertTrue(
      in_array($editorRole->id, $imagesField->viewRoles),
      "images field has view access for editor role."
    );

    // get the test page
    $skyscraper = Utils::pages()->get("id=" . self::PAGE_ID);

    // get image from the images field
    $image = $skyscraper->images->first();

    // our thumb dimensions
    $thumbWidth = 456;
    $thumbHeight = 321;

    // make sure the thumbnail does exist before we query it
    $actualThumb = $image->size($thumbWidth, $thumbHeight);

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

    $res = self::execute($query);
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");

    $expectedThumb = $res->data->skyscraper->list[0]->images[0]->size;

    // make sure it responded the correct thumbnail
    self::assertEquals(
      $expectedThumb->url,
      $actualThumb->url,
      "Retrieves correct image url."
    );
    self::assertEquals(
      $expectedThumb->width,
      $actualThumb->width,
      "Retrieves correct image width."
    );
    self::assertEquals(
      $expectedThumb->height,
      $actualThumb->height,
      "Retrieves correct image height."
    );
  }
}
