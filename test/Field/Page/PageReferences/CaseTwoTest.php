<?php

/**
 * When the requested page template is legal but not
 * the references' templates. The `references` field returns
 * empty list.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageReferences;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseTwoTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["architect"],
    "legalPageFields" => ["references"],
  ];

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $query = "{
  		architect (s: \"id=$architect->id\") {
  			list {
  				references {
            getTotal
            list {
              name
            }
          }
  			}
  		}
  	}";
    $res = self::execute($query);
    self::assertEquals(
      0,
      count($res->data->architect->list[0]->references->list),
      "Returns empty list when no access to references pages template."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
