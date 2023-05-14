<?php

/**
 * When user got access to requested page template but not
 * to the references' templates. The `references` field returns
 * empty list.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageReferences;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseThreeTest extends GraphQLTestCase
{
  public static function getSettings()
  {

    $editorRole = Utils::roles()->get("editor");

    return [
      "login" => "editor",
      "legalTemplates" => ["architect", "skyscraper"],
      "legalPageFields" => ["references"],
      "access" => [
        "templates" => [
          [
            "name" => "architect",
            "roles" => [$editorRole->id],
          ],
        ],
      ]
    ];
  }

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
    self::assertNotEquals(
      count($architect->references()),
      count($res->data->architect->list[0]->references->list),
      "Returned number of reference pages is not the same as the actual number of reference pages."
    );
    self::assertEquals(
      0,
      count($res->data->architect->list[0]->references->list),
      "Returns empty list when user has no access to referenced pages template."
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
