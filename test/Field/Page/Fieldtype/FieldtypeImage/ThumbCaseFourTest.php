<?php

/**
 * When user is editor, `view` access is enabled to template and `view` & `edit`
 * access is enabled to field, the `size` field creates thumb image and returns it.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypeImageThumbCaseFourTest extends GraphQLTestCase {

  const PAGE_ID = 4189;

  public static function getSettings()
  {
    $editorRole = Utils::roles()->get('editor');
    return [
      'login' => 'editor',
      'legalTemplates' => ['skyscraper'],
      'legalFields' => ['images'],
      'legalPageImageFields' => ['size'],
      'legalPageFileFields' => ['url'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => [$editorRole->id],
          ],
        ],
        'fields' => [
          [
            'name' => 'images',
            'viewRoles' => [$editorRole->id],
            'editRoles' => [$editorRole->id],
          ],
        ],
      ],
    ];
  }

  public function testThumbCreate()
  {
    // make sure user is logged in as an editor
    assertEquals(Utils::user()->name, 'editor', 'Logged in as an editor.');
    assertTrue(Utils::user()->hasRole('editor'), 'Editor has editor role.');

    // make sure editor has explicit rights to view the skyscraper pages
    assertTrue(
      Utils::templates()->get('skyscraper')->hasRole('editor', 'view'),
      'skyscraper template has view access for editor role.'
    );

    // make sure editor has explicit right to view and edit images field
    $editorRole = Utils::roles()->get('editor');
    $imagesField = Utils::fields()->get('images');
    assertTrue(
      in_array($editorRole->id, $imagesField->viewRoles),
      'images field has view access for editor role.'
    );
    // assertTrue(
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
    assertEquals(
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
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');

    // the thumb created by graphql
    $actualThumb = $res->data->skyscraper->list[0]->images[0]->size;

    // the created thumb's filename
    $filename = realpath($GLOBALS['pwDir'] . $actualThumb->url);

    // make sure it created the thumbnail
    assertTrue(file_exists($filename), 'Editor creates the thumbnail.');
    assertTrue(is_file($filename), 'The created thumbnail is a file.');
    
    // expected thumb
    $expectedThumb = $image->size($thumbWidth, $thumbHeight);

    // make sure it created the correct thumbnail
    assertEquals($expectedThumb->url, $actualThumb->url, 'Correct url for created thumbnail.');
    assertEquals($expectedThumb->width, $actualThumb->width, 'Correct width for created thumbnail.');
    assertEquals($expectedThumb->height, $actualThumb->height, 'Correct height for created thumbnail.');

    // clean up after test
    unlink($filename);
  }

}