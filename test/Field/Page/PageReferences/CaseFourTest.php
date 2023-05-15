<?php

/**
 * When user got access to requested page template but not
 * to the references' templates. The `references` field returns
 * empty list.
 */

namespace ProcessWire\GraphQL\Test\FieldtypePageReferences;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
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
          [
            "name" => "skyscraper",
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
    self::assertEquals(
      $architect->references()[0]->name,
      $res->data->architect->list[0]->references->list[0]->name,
      "Retrieves correct reference page at 0."
    );
    self::assertEquals(
      $architect->references()[1]->name,
      $res->data->architect->list[0]->references->list[1]->name,
      "Retrieves correct reference page at 1."
    );
    self::assertEquals(
      $architect->references->count,
      $res->data->architect->list[0]->references->getTotal,
      "Retrieves correct amount of reference pages."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
