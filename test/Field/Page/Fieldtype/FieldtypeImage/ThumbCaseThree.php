<?php

/**
 * When user is editor and `view` access is enabled to template and field,
 * the `size` field returns empty thumb object if it does not exist.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use ProcessWire\Field;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class FieldtypeImageThumbCaseThreeTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'skyscraper';
  const FIELD_NAME = 'images';
  const PAGE_ID = 4184;

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
    $editorRole = Utils::roles()->get('skyscraper-editor');
    Utils::templates()->get('skyscraper')->setRoles([$editorRole->id], 'view');

    // grant view access on images field to skyscrrapers-editor role
    $field = Utils::fields()->get('images');
    $field->flags = $field->flags | Field::flagAccess;
    $field->setRoles('view', [$editorRole->id]);
  }

  public static function tearDownAfterClass()
  {
    // remove explicit view access on images field for skyscrapers-editor role
    $field = Utils::fields()->get('images');
    $field->setRoles('view', []);

    // remove explicit view access on skyscraper template for skyscrapers-editor role
    $template = Utils::templates()->get('skyscraper');
    $template->setRoles([], 'view');
    
    // logout the editor user
    Utils::session()->logout();

    parent::tearDownAfterClass();
  }

  public function testThumbCreate()
  {
    // make sure user is logged in as an editor
    $this->assertEquals(Utils::user()->name, 'editor', 'Logged in as an editor.');
    $this->assertTrue(Utils::user()->hasRole('skyscraper-editor'), 'Editor has skyscraper-editor role.');

    // make sure editor has explicit rights to view the skyscraper pages
    $this->assertTrue(
      Utils::templates()->get('skyscraper')->hasRole('skyscraper-editor', 'view'),
      'skyscraper template has view access for skyscraper-editor role.'
    );

    // make sure editor has explicit right to view images field
    $editorRole = Utils::roles()->get('skyscraper-editor');
    $imagesField = Utils::fields()->get('images');
    $this->assertTrue(
      in_array($editorRole->id, $imagesField->viewRoles),
      'images field has view access for skyscraper-editor role.'
    );
    
    // get the test page
    $skyscraper = Utils::pages()->get("id=" . self::PAGE_ID);
    
    // get image from the images field
    $image = $skyscraper->images->first();

    // our thumb dimensions
    $thumbWidth = 654;
    $thumbHeight = 123;

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

    $res = $this->execute($query);
    $expectedThumb = $res->data->skyscraper->list[0]->images[0]->size;
    
    // make sure it responded the correct thumbnail
    $this->assertEquals('', $expectedThumb->url, 'Retrieves correct image url.');
    $this->assertEquals(0, $expectedThumb->width, 'Retrieves correct image width.');
    $this->assertEquals(0, $expectedThumb->height, 'Retrieves correct image height.');
  }

}