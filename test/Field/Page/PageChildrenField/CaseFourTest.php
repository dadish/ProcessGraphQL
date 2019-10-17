<?php

/**
 * `children` field's selector respects access rules.
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageChildrenFieldCaseFourTest extends GraphQLTestCase {

  const accessRules = [
    'login' => 'admin',
    'legalTemplates' => ['home', 'cities'],
    'legalPageFields' => ['children'],
  ];

  
  public function testValue()
  {
    $home = Utils::pages()->get("template=home");
    $query = "{
      home (s: \"id=$home->id\") {
        list {
          children (s: \"template=cities|architects\") {
            list {
              name
            }
          }
        }
      }
    }";
    $res = self::execute($query);
    $children = $home->children("template=cities"); // only cities template is allowed
    $this->assertEquals($children->count, count($res->data->home->list[0]->children->list), 'Returns the correct number of pages.');
    $this->assertEquals($children[0]->name, $res->data->home->list[0]->children->list[0]->name, 'Returns the correct page at 0.');
  }

}