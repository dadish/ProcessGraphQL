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

  const TEMPLATE_NAME = 'skyscraper';
  const FIELD_NAME = 'images';
  const PAGE_ID = 4189;

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();

    $module = Utils::module();
    $module->legalTemplates = [self::TEMPLATE_NAME];
    $module->legalFields = [self::FIELD_NAME];
    $module->legalPageImageFields = array_merge($module->legalPageImageFields, ['size']);
    $module->legalPageFileFields = array_merge($module->legalPageFileFields, ['url']);

    // login as an editor
    Utils::session()->login('editor', Utils::config()->testUsers['editor']);

    // grant view access on skyscraper template to skyscrapers-editor role
    $editorRole = Utils::roles()->get('editor');
    Utils::templates()->get('skyscraper')->useRoles = 1;
    Utils::templates()->get('skyscraper')->setRoles([$editorRole->id], 'view');

    // grant view and edit access on images field to skyscrrapers-editor role
    $field = Utils::fields()->get('images');
    $field->flags = $field->flags | Field::flagAccess;
    $field->setRoles('view', [$editorRole->id]);
    $field->setRoles('edit', [$editorRole->id]);
  }

  public static function tearDownAfterClass()
  {
    // remove explicit view and edit access on images field for skyscrapers-editor role
    $field = Utils::fields()->get('images');
    $field->setRoles('view', []);
    $field->setRoles('edit', []);

    // remove explicit view access on skyscraper template for skyscrapers-editor role
    $template = Utils::templates()->get('skyscraper');
    $template->setRoles([], 'view');
    Utils::templates()->get('skyscraper')->useRoles = 0;
    
    // logout the editor user
    Utils::session()->logout();

    parent::tearDownAfterClass();
  }

  public function testThumbCreate()
  {
    // make sure user is logged in as an editor
    $this->assertEquals(Utils::user()->name, 'editor', 'Logged in as an editor.');
    $this->assertTrue(Utils::user()->hasRole('editor'), 'Editor has editor role.');

    // make sure editor has explicit rights to view the skyscraper pages
    $this->assertTrue(
      Utils::templates()->get('skyscraper')->hasRole('editor', 'view'),
      'skyscraper template has view access for editor role.'
    );

    // make sure editor has explicit right to view and edit images field
    $editorRole = Utils::roles()->get('editor');
    $imagesField = Utils::fields()->get('images');
    $this->assertTrue(
      in_array($editorRole->id, $imagesField->viewRoles),
      'images field has view access for editor role.'
    );
    // $this->assertTrue(
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
    $this->assertTrue(file_exists($filename), 'Editor creates the thumbnail.');
    $this->assertTrue(is_file($filename), 'The created thumbnail is a file.');
    
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