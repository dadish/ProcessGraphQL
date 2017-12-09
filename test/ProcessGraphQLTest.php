<?php

use PHPUnit\Framework\TestCase;
use Youshido\GraphQL\Type\Scalar\StringType;
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
    $default = '/processwire/graphql/';
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
    $variables = 'variables in $_POST["variablses"]';
    $_POST['variables'] = $variables;
    $request = Utils::module()->getRequest();
    $this->assertEquals($variables, $request['variables']);
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
    $response = Utils::module()->executeGraphQL();
    $resObj = json_decode($response);
    $this->assertEquals('Must provide an operation.', $resObj->errors[0]->message);

    // accepts GraphQL request via arguments
    $payload = '{ me { name } }';
    $response = Utils::module()->executeGraphQL($payload);
    $resObj = json_decode($response);
    $this->assertEquals('guest', $resObj->data->me->name, 'Accepts request via arguments');
    
    // accepts GraphQL request via $_POST variable
    $_POST['payload'] = $payload;
    $response = Utils::module()->executeGraphQL();
    $resObj = json_decode($response);
    $this->assertEquals('guest', $resObj->data->me->name, 'Accepts request via $_POST variable');
  }
  
  public function testGetQueryHook()
  {
    Utils::wire()->addHookAfter('ProcessGraphQL::getQuery', function ($event) {
      $query = $event->return;
      $query->addField('hello', [
          'type' => new StringType(),
          'resolve' => function () {
              return 'world!';
          }
      ]);
    });

    $response = Utils::module()->executeGraphQL('{ hello }');
    $resObj = json_decode($response);
    $this->assertEquals('world!', $resObj->data->hello);
  }

  public function testGetMutationHook()
  {
    Utils::module()->legalEditTemplates = ['home'];
    Utils::wire()->addHookAfter('ProcessGraphQL::getMutation', function ($event) {
      $query = $event->return;
      $query->addField('zombie', [
          'type' => new StringType(),
          'resolve' => function () {
              return 'apocalypse';
          }
      ]);
    });

    $response = Utils::module()->executeGraphQL('mutation { zombie }');
    $resObj = json_decode($response);
    $this->assertEquals('apocalypse', $resObj->data->zombie);
  }
  
  public function testGetQueryGetMutationHooks()
  {
    $response = Utils::module()->executeGraphQL('query { hello } mutation { zombie }');
    $resObj = json_decode($response);
    $this->assertEquals('world!', $resObj->data->hello);
    $this->assertEquals('apocalypse', $resObj->data->zombie);
  }
}