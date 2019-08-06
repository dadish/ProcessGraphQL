<?php namespace ProcessWire\GraphQL\Field\Auth;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\AuthResponseType;

class Login
{
  public static function field()
  {
    return [
      'type' => AuthResponseType::type(),
      'name' => 'login',
      'description' => 'Allows you to authenticate into the app.',
      'args' => [
        'name' => Type::string(),
        'pass' => Type::string(),
      ],
      'resolve' => function ($pages, $args) {
        $session = \ProcessWire\wire('session');
        $username = $args['name'];
        $password = $args['pass'];
        $user = $session->login($username, $password);
        $response = [];
        if (is_null($user)) {
          $response['statusCode'] = 401;
          $response['message'] = 'Wrong name and/or pass.';
        } else {
          $response['statusCode'] = 200;
          $response['message'] = 'Successful login!';
        }
        return $response;
      }
    ];
  }
}
