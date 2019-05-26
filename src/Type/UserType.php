<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\CacheTrait;

class UserType
{
  use CacheTrait;

  public static $name = 'User';

  public static $description = 'ProcessWire User.';

  public static function buildType()
  {
    return new ObjectType([
      'name' => self::$name,
      'description' => self::$description,
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
          'description' => "The user's id.",
        ],
      ]
    ]);
  }
}