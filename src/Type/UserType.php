<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType
{
  public static function create()
  {
    return new ObjectType([
      'name' => 'User',
      'description' => 'Represents ProcessWire User.',
      'fields' => [
        'name' => [
          'type' => Type::nonNull(Type::string()),
          'description' => "The user's login name.",
        ],
        'email' => [
          'type' => Type::nonNull(Type::string()),
          'description' => "The user's email address.",
        ],
        'id' => [
          'type' => Type::nonNull(Type::id()),
          'description' => "The user's email address.",
        ],
      ]
    ]);
  }
}