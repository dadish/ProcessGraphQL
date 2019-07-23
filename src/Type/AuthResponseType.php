<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\CacheTrait;

class AuthResponseType
{
  use CacheTrait;
  public static function type()
  {
    return self::cache('default', function () {
      return new ObjectType([
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
    });
  }
}
