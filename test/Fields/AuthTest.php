<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase {

  use TestHelperTrait;

  public function testLogin()
  {
    // first make sure the user is guest user
    $user = $this->wire('user');
    $this->assertEquals('guest', $user->name);
    $this->assertTrue($user->isGuest());

    // the admin credentials
    $name = 'admin';
    $pass = $this->wire('config')->testUsers['admin'];

    // now login via graphql
    $loginRequest ="{
      login(name: \"$name\", pass: \"$pass\") {
        statusCode,
        message
      }
    }";
    $response = $this->module()->executeGraphQL($loginRequest);
    $respObj = json_decode($response);
    print_r($respObj);

    $user = $this->wire('user');
    $this->assertEquals('admin', $user->name);
    $this->assertTrue($user->isSuperuser());
  }

}