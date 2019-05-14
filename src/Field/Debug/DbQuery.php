<?php namespace ProcessWire\GraphQL\Field\Debug;

use GraphQL\Type\Definition\Type;

class DbQuery
{
  public static function field()
  {
    return [
      'type' => Type::listOf(Type::string()),
      'name' => 'dbQuery',
      'description' => 'The queries made to database to fulfill this request. Available in debug mode.',
      'resolve' => function () {
        return \ProcessWire\Database::getQueryLog();
      }
    ];
  }
}
