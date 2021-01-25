<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;

class EditorCanNotViewFieldTest extends GraphqlTestCase
{
  public static function getSettings()
  {
    $editorRole = Utils::roles()->get("editor");
    return [
      "login" => "editor",
      "legalTemplates" => ["skyscraper"],
      "legalFields" => ["height"],
      "access" => [
        "templates" => [
          [
            "name" => "skyscraper",
            "roles" => [$editorRole->id],
          ],
        ],
      ],
    ];
  }

  public function testEditorCanNotViewField()
  {
    $target = Utils::pages()->get("template=skyscraper, sort=random");
    $query = "{
      skyscraper(s: \"id={$target->id}\") {
        list {
          id
          name
          height
        }
      }
    }";
    $res = self::execute($query);
    self::assertEquals(
      1,
      count($res->errors),
      "Editor can not view the height field if it does not have explicit access to it."
    );
    assertStringContainsString("height", $res->errors[0]->message);
  }
}
