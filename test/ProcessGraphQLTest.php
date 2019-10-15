<?php

namespace ProcessWire\GraphQL\Test;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Test\GraphQLTestCase;
use ProcessWire\GraphQL\Utils;

class ProcessGraphQLTest extends GraphQLTestCase {

  public function tearDown()
  {
    $_POST['payload'] = null;
    $_POST['query'] = null;
    $_POST['variables'] = null;
    $_SERVER['CONTENT_TYPE'] = null;
  }

  public function testGetGraphQLServerUrl()
  {
    $module = Utils::module();
    $default = '/processwire/setup/graphql/';
    $this->assertEquals($default, $module->getGraphQLServerUrl());

    $custom = '/custom/graphql/server/path/';
    $module->GraphQLServerUrl = $custom;
    $this->assertEquals($custom, $module->getGraphQLServerUrl());
  }

  public function testGetRequestForPost()
  {
    // payload is null by default
    $request = Utils::module()->getRequest();
    $this->assertNull($request['payload'], 'Request payload should be null by default');

    // payload could be set via $_POST['payload']
    $payload = 'payload in $_POST["payload"]';
    $_POST['payload'] = $payload;
    $request = Utils::module()->getRequest();
    $this->assertEquals($payload, $request['payload']);

    // payload could be set via $_POST['query']
    $payload = 'payload in $_POST["query"]';
    $_POST['query'] = $payload;
    $request = Utils::module()->getRequest();
    $this->assertEquals($payload, $request['payload']);

    // variables is null by default
    $request = Utils::module()->getRequest();
    $this->assertNull($request['variables']);

    // variables could be set via $_POST['variables']
    $variables = '{ one: 1, two: "two" }';
    $_POST['variables'] = $variables;
    $request = Utils::module()->getRequest();
    $this->assertEquals(json_decode($variables), $request['variables'], 'variables in $_POST["variables"]');
  }

  public function testGetRequestForPhpInput()
  {
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    
    // payload & variables are null by default
    $request = Utils::module()->getRequest();
    $this->assertNull($request['payload']);
    $this->assertNull($request['variables']);

    // payload & variables could be set via php://input
    
    // NOTE: need to figure out the way to test php://input
  }

  public function testExecuteGraphQL()
  {
    // should return GraphQL response with errors when no payload/query is provided
    $res = Utils::module()->executeGraphQL();
    $this->assertEquals('Syntax Error: Unexpected <EOF>', $res->errors[0]->message);

    // accepts GraphQL request via arguments
    $payload = '{ me { name } }';
    $res = Utils::module()->executeGraphQL($payload);
    $this->assertEquals('guest', $res->data->me->name, 'Accepts request via arguments');
    
    // accepts GraphQL request via $_POST variable
    $_POST['payload'] = $payload;
    $res = Utils::module()->executeGraphQL();
    $this->assertEquals('guest', $res->data->me->name, 'Accepts request via $_POST variable');
  }
  
  public function testGetQueryFieldsHook()
  {
    Utils::wire()->addHookAfter('ProcessGraphQL::getQueryFields', function ($event) {
      $fields = $event->return;
      $fields[] = [
        'name' => 'hello',
        'type' => Type::string(),
        'resolve' => function() {
          return 'world!';
        }
      ];
      $event->return = $fields;
    });

    $res = self::execute('{ hello }');
    $this->assertEquals('world!', $res->data->hello);
  }

  public function testGetMutationFieldsHook()
  {
    Utils::module()->legalEditTemplates = ['home'];
    Utils::wire()->addHookAfter('ProcessGraphQL::getMutationFields', function ($event) {
      $fields = $event->return;
      $fields[] = [
        'name' => 'zombie',
        'type' => Type::string(),
        'resolve' => function () {
          return 'apocalypse';
        }
      ];
      $event->return = $fields;
    });

    $res = self::execute('mutation { zombie }');
    $this->assertEquals('apocalypse', $res->data->zombie);
  }
}