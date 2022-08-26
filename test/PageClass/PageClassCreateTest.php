<?php

namespace ProcessWire\GraphQL\Test\PageClass;

use ProcessWire\BasicPagePage;
use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\HookEvent;

class PageClassCreateTest extends GraphqlTestCase
{
  const settings = [
    "login" => "admin",
    "legalTemplates" => ["basic-page", "home"],
    "legalFields" => ["title"],
  ];

  public function testCustomGet()
  {
    $originalValue = "original value";
    $expectedValue = "expected value";
    // Attach a hook pages save.
    Utils::pages()->addHookBefore("Pages::save", function (
      HookEvent $hookEvent
    ) use (&$originalValue, $expectedValue) {
      $page = $hookEvent->arguments(0);
      if ($page instanceof BasicPagePage) {
        $originalValue = $expectedValue;
      }
    });

    // create basic page via graphql
    $query = 'mutation CreateBasicPage ($page: BasicPageCreateInput!) {
  		createBasicPage (page: $page) {
        title
        id
  		}
  	}';
    $variables = [
      "page" => [
        "parent" => "1",
        "name" => "basic-page-with-custom-class-created",
        "title" => "Incorrect title",
      ],
    ];

    self::execute($query, $variables);
    self::assertEquals(
      $originalValue,
      $expectedValue,
      "Graphql created basic page does not use the custom page class."
    );
  }
}
