<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;

class FieldtypeImageThumbCreationTest extends GraphQLTestCase {

  const TEMPLATE_NAME = 'skyscraper';
  const FIELD_NAME = 'images';
  const FIELD_TYPE = 'FieldtypeImage';

  use FieldtypeTestTrait;

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    $module = Utils::module();
    $module->legalTemplates = [self::TEMPLATE_NAME];
    $module->legalFields = [self::FIELD_NAME];
    $module->legalPageImageFields = array_merge($module->legalPageImageFields, ['size']);
    $module->legalPageFileFields = array_merge($module->legalPageFileFields, ['url']);
    Utils::session()->login('admin', Utils::config()->testUsers['admin']);
  }

  public static function tearDownAfterClass()
  {
    Utils::session()->logout();
    parent::tearDownAfterClass();
  }

  public function testThumbCreation()
  {
    $skyscraper = Utils::pages()->get("template=skyscraper, images.count=1");
    $image = $skyscraper->images->first();

    $thumbWidth = 400;
    $thumbHeight = 300;

    
    // make sure the thumbnail does not exist before we create it
    $this->assertEquals(
      0,
      $image->getVariations()->count,
      'No thumbnail prior to test.'
    );

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
    $thumb = $res->data->skyscraper->list[0]->images[0]->size;
    $filename = realpath($GLOBALS['pwDir'] . $thumb->url);
    
    // make sure it created the thumbnail
    $this->assertTrue(file_exists($filename), 'Creates the thumbnail.');
    $this->assertEquals($image->size($thumbWidth, $thumbHeight)->url, $thumb->url);

    // clean up after test
    unlink($filename);
  }

}