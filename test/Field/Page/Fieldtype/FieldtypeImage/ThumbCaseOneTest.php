<?php

/**
 * Superuser can view and create thumb via `size` field.
 * No need for explicit access settings.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypeImageThumbCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['images'],
    'legalPageImageFields' => ['size'],
    'legalPageFileFields' => ['url'],
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
    $this->assertEquals(
      0,
      $image->getVariations()->count,
      'No thumbnail prior to test.'
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

    // the thumb created by graphql
    $actualThumb = $res->data->skyscraper->list[0]->images[0]->size;

    // the created thumb's filename
    $filename = realpath($GLOBALS['pwDir'] . $actualThumb->url);

    // make sure it created the thumbnail
    $this->assertTrue(file_exists($filename), 'Admin creates the thumbnail.');
    $this->assertTrue(is_file($filename), 'Created thumbnail is a file.');
    
    // expected thumb
    $expectedThumb = $image->size($thumbWidth, $thumbHeight);

    // make sure it created the correct thumbnail
    $this->assertEquals($expectedThumb->url, $actualThumb->url, 'Correct url for created thumbnail.');
    $this->assertEquals($expectedThumb->width, $actualThumb->width, 'Correct width for created thumbnail.');
    $this->assertEquals($expectedThumb->height, $actualThumb->height, 'Correct height for created thumbnail.');

    // clean up after test
    unlink($filename);
  }
}