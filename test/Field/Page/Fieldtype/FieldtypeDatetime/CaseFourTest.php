<?php

namespace ProcessWire\GraphQL\Test\FieldtypeDatetime;

/**
 * Accepts a string as an input value.
 */

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Test\GraphQLTestCase;

class CaseFourTest extends GraphQLTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["architect"],
    "legalFields" => ["born"],
  ];

  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect");
    $format = "d/m/Y H:i:s";
    $query = 'mutation updatePage($page: ArchitectUpdateInput!){
      architect: updateArchitect(page: $page) {
          born
      }
    }';
    $variables = [
      "page" => [
        "id" => $architect->id,
        "born" => "01/02/2020 01:02:03",
      ],
    ];
    $res = self::execute($query, $variables);
    self::assertEquals(
      $architect->getUnformatted("born"),
      $res->data->architect->born,
      "Accepts string as an input value."
    );
    self::assertObjectNotHasPropertyOrAttribute("errors", $res, "There are errors.");
  }
}
