<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AuthResponseType
{
  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    self::$type = new ObjectType([
      'name' => 'AuthResponse',
      'description' => 'Object type that represents the authentication response.',
      'fields' => [
        'statusCode' => [
          'type' => Type::nonNull(Type::int()),
          'description' => 'The authentication status code. E.g. 200 if successful.',
        ],
        'message' => [
          'type' => Type::nonNull(Type::string()),
          'description' => 'Homan readable message of the authentication reponse. E.g. "successful login!"',
        ],
      ]
    ]);

    return self::$type;
  }
}
