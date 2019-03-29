<?php namespace ProcessWire\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema as GraphQLSchema;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageArray;

class Schema extends GraphQLSchema
{
  public static function create()
  {
    /**
     * Query
     */ 
    $schema = new GraphQLSchema([
      'query' => self::buildQueryType(),
    ]);

    return $schema;
  }

  public static function buildQueryType()
  {
    $moduleConfig = Utils::moduleConfig();
    $queryFields = [];
    $queryFields[] = PageArray::asField();

    $queryType = new ObjectType([
      'name' => 'Query',
      'fields' => $queryFields,
    ]);

    return $queryType;
  }
}