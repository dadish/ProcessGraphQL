<?php

namespace ProcessWire\GraphQL\Test;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class ProcessGraphQLTest extends GraphQLTestCase
{
  public function tearDown(): void
  {
    $_POST["payload"] = null;
    $_POST["query"] = null;
    $_POST["variables"] = null;
    $_SERVER["CONTENT_TYPE"] = null;
  }

  public function testGetGraphQLServerUrl()
  {
    $module = Utils::module();
    $default = "/processwire/setup/graphql/";
    self::assertEquals($default, $module->getGraphQLServerUrl());

    $custom = "/custom/graphql/server/path/";
    $module->GraphQLServerUrl = $custom;
    self::assertEquals($custom, $module->getGraphQLServerUrl());
  }

  public function testGetRequestForPost()
  {
    // payload is empty string by default
    $request = Utils::module()->getRequest();
    self::assertEquals(
      "",
      $request["payload"],
      "Request payload should be null by default"
    );

    // payload could be set via $_POST['payload']
    $payload = 'payload in $_POST["payload"]';
    $_POST["payload"] = $payload;
    $request = Utils::module()->getRequest();
    self::assertEquals($payload, $request["payload"]);

    // payload could be set via $_POST['query']
    $payload = 'payload in $_POST["query"]';
    $_POST["query"] = $payload;
    $request = Utils::module()->getRequest();
    self::assertEquals($payload, $request["payload"]);

    // variables is null by default
    $request = Utils::module()->getRequest();
    self::assertNull($request["variables"]);

    // variables could be set via $_POST['variables']
    $variables = '{ one: 1, two: "two" }';
    $_POST["variables"] = $variables;
    $request = Utils::module()->getRequest();
    self::assertEquals(
      json_decode($variables),
      $request["variables"],
      'variables in $_POST["variables"]'
    );
  }

  public function testGetRequestForPhpInput()
  {
    $_SERVER["CONTENT_TYPE"] = "application/json";

    // payload & variables are empty by default
    $request = Utils::module()->getRequest();
    self::assertEquals("", $request["payload"]);
    self::assertNull($request["variables"]);

    // payload & variables could be set via php://input

    // NOTE: need to figure out the way to test php://input
  }

  public function testExecuteGraphQL()
  {
    // should return GraphQL response with errors when no payload/query is provided
    $res = self::execute();
    self::assertEquals(
      "Syntax Error: Unexpected <EOF>",
      $res->errors[0]->message
    );

    // accepts GraphQL request via arguments
    $payload = "{ me { name } }";
    $res = self::execute($payload);
    self::assertEquals(
      "guest",
      $res->data->me->name,
      "Accepts request via arguments"
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");

    // accepts GraphQL request via $_POST variable
    $_POST["payload"] = $payload;
    $res = self::execute();
    self::assertEquals(
      "guest",
      $res->data->me->name,
      'Accepts request via $_POST variable'
    );
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }

  public function testGetQueryFieldsHook()
  {
    Utils::wire()->addHookAfter("ProcessGraphQL::getQueryFields", function (
      $event
    ) {
      $fields = $event->return;
      $fields[] = [
        "name" => "hello",
        "type" => Type::string(),
        "resolve" => function () {
          return "world!";
        },
      ];
      $event->return = $fields;
    });

    $res = self::execute("{ hello }");
    self::assertEquals("world!", $res->data->hello);
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }

  public function testGetMutationFieldsHook()
  {
    Utils::module()->legalEditTemplates = ["home"];
    Utils::wire()->addHookAfter("ProcessGraphQL::getMutationFields", function (
      $event
    ) {
      $fields = $event->return;
      $fields[] = [
        "name" => "zombie",
        "type" => Type::string(),
        "resolve" => function () {
          return "apocalypse";
        },
      ];
      $event->return = $fields;
    });

    $res = self::execute("mutation { zombie }");
    self::assertEquals("apocalypse", $res->data->zombie);
    self::assertObjectNotHasProperty("errors", $res, "There are errors.");
  }
}
