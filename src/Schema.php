<?php namespace ProcessWire\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema as GraphQLSchema;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\UserType;

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

    // add lagal templates
    foreach ($moduleConfig->legalViewTemplates as $template) {
      $queryFields[] = PageArrayType::asField($template);
    }

    // User. The `me`
    if ($moduleConfig->meQuery) {
      $queryFields[] = [
        'name' => 'me',
        'description' => 'The current user of the app.',
        'type' => UserType::type(),
        'resolve' => function() {
          return \ProcessWire\wire('user');
        }
      ];
    }

    // let the user modify the query operation
    Utils::module()->getQueryFields($queryFields);

    $queryType = new ObjectType([
      'name' => 'Query',
      'fields' => $queryFields,
    ]);

    return $queryType;
  }
}