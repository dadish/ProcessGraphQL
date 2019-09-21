<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Cache;

class AuthResponseType
{
  public static $name = 'AuthResponse';
  
  public static function type()
  {
    return Cache::type(self::$name, function () {
      return new ObjectType([
        'name' => self::$name,
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
    });
  }
}
