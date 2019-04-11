<?php namespace ProcessWire\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema as GraphQLSchema;
use ProcessWire\Pages as PWPages;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageArrayType;

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

    foreach ($moduleConfig->legalViewTemplates as $template) {
      $queryFields[] = [
        'name' => $template->name,
        'description' => PageArrayType::templatedTypeDescription($template),
        'type' => PageArrayType::type($template),
        'resolve' => function (PWPages $pages) {
          return $pages;
        }
      ];
    }

    $queryType = new ObjectType([
      'name' => 'Query',
      'fields' => $queryFields,
    ]);

    return $queryType;
  }
}