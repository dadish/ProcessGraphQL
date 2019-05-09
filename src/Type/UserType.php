<?php namespace ProcessWire\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType
{
  public static $name = 'User';

  public static $description = 'ProcessWire User.';

  private static $type;

  public static function type()
  {
    if (self::$type) {
      return self::$type;
    }

    $type = new ObjectType([
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

    self::$type = $type;
    return self::$type;
  }
}