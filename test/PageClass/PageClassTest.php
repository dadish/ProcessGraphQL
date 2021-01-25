<?php

namespace ProcessWire\GraphQL\Test\PageClass;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\HookEvent;

class PageClassTest extends GraphqlTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["basic-page", "home"],
    "legalFields" => ["title"],
  ];

  public function testCustomGet()
  {
    $expectedKey = "title";
    $expectedValue = "Custom Basic Page Class";
    // get a basic-page page
    $target = Utils::pages()->get("template=basic-page, name=about");

    // remember the original title
    $targetOriginalTitle = $target->title;

    // replace the title with our own title via customGet hook from BasicPageClass
    $hookId = Utils::pages()->addHookAfter(
      "BasicPagePage::customGet",
      function (HookEvent $ev) use ($expectedKey, $expectedValue) {
        if ($ev->arguments("key") === $expectedKey) {
          $ev->return = $expectedValue;
        }
      }
    );

    // fetch basic-page page via graphql
    $query = 'query getBasicPage ($s: Selector!) {
  		basicPage (s: $s) {
  			list {
          title
  			  id
        }
  		}
  	}';
    $variables = [
      "s" => "id={$target->id}",
    ];

    $res = self::execute($query, $variables);
    self::assertNotEquals($targetOriginalTitle, $expectedValue);
    self::assertEquals($target->id, $res->data->basicPage->list[0]->id);
    self::assertEquals($expectedValue, $res->data->basicPage->list[0]->title);

    Utils::pages()->removeHook($hookId);
    $res = self::execute($query, $variables);
    self::assertEquals($target->id, $res->data->basicPage->list[0]->id);
    self::assertEquals(
      $targetOriginalTitle,
      $res->data->basicPage->list[0]->title
    );
  }
}
