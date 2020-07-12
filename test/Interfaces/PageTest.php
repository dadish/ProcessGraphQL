<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

/**
 * It supports page interfaces.
 */
use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageInterfaceTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['city', 'skyscraper'],
    'legalFields' => ['height', 'title', 'year'],
    'legalPageFields' => ['children']
  ];

  public function testValue()
  {

    $city = Utils::pages()->get("template=city");
    $query = "{
      city(s: \"id=$city->id\") {
        list {
          name
          children{
            list{
              ... on SkyscraperPage {
                title
                height
                year
              }
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    assertEquals($city->name, $res->data->city->list[0]->name, 'Retrieves correct city name.');
    assertEquals($city->children[0]->title, $res->data->city->list[0]->children->list[0]->title, 'Retrieves correct skyscraper title.');
    assertEquals($city->children[0]->height, $res->data->city->list[0]->children->list[0]->height, 'Retrieves correct skyscraper height.');
    assertEquals($city->children[0]->year, $res->data->city->list[0]->children->list[0]->year, 'Retrieves correct skyscraper year.');
    assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}