<?php namespace ProcessWire\GraphQL\Field\Auth;

use ProcessWire\GraphQL\Type\AuthResponseType;

class Logout
{
  public static function field()
  {
    return [
      'type' => AuthResponseType::type(),
      'name' => 'logout',
      'description' => 'Allows you to logout from the app.',
      'resolve' => function () {
        $session = \ProcessWire\wire('session');
        $response = [];
        try {
          $session = $session->logout();
          $response['statusCode'] = 200;
          $response['message'] = 'Successful logout!';
        } catch (Exception $error) {
          $response['statusCode'] = 500;
          $response['message'] = 'Could not logout: ' . $error->getMessage();
        }
        return $response;
      }
    ];
  }
}
