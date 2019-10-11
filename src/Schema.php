<?php namespace ProcessWire\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema as GraphQLSchema;

use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Cache;
use ProcessWire\GraphQL\Type\PageArrayType;
use ProcessWire\GraphQL\Type\UserType;
use ProcessWire\GraphQL\Field\Auth\Login;
use ProcessWire\GraphQL\Field\Auth\Logout;
use ProcessWire\GraphQL\Field\Debug\DbQuery;
use ProcessWire\GraphQL\Field\Mutation\CreatePage;
use ProcessWire\GraphQL\Field\Mutation\UpdatePage;

class Schema
{
  private static $schema = null;

  public static function getSchema()
  {
    if (is_null(self::$schema)) {
      self::build();
    }

    return self::$schema;
  }

  public static function build()
  {
    Cache::clear();
    $schema = [];

    $query = self::buildQuery();
    if ($query) {
      $schema['query'] = $query;
    }

    $mutation = self::buildMutation();
    if ($mutation) {
      $schema['mutation'] = $mutation;
    }

    self::$schema = new GraphQLSchema($schema);
  }

  public static function buildQuery()
  {
    $moduleConfig = Utils::moduleConfig();
    $queryFields = [];

    // add lagal templates
    foreach ($moduleConfig->legalViewTemplates as $template) {
      $queryFields[] = PageArrayType::field($template);
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

    // Auth
    if ($moduleConfig->authQuery) {
      if (Utils::user()->isLoggedin()) {
        $queryFields[] = Logout::field();
      } else {
        $queryFields[] = Login::field();
      }
    }

    // Debugging
    if (\ProcessWire\Wire('config')->debug) {
      $queryFields[] = DbQuery::field();
    }

    // let the user modify the query operation
    $queryFields = Utils::module()->getQueryFields($queryFields);

    if (count($queryFields)) {
      return new ObjectType([
        'name' => 'Query',
        'fields' => $queryFields,
      ]);
    }

    return null;
  }

  public static function buildMutation()
  {
    $moduleConfig = Utils::moduleConfig();
    $mutationFields = [];

    // CreatePage
    foreach ($moduleConfig->legalCreateTemplates as $template) {
      $mutationFields[] = CreatePage::field($template);
    }

    // UpdatePage
    foreach ($moduleConfig->legalEditTemplates as $template) {
      $mutationFields[] = UpdatePage::field($template);
    }

    // let the user modify the query operation
    $mutationFields = Utils::module()->getMutationFields($mutationFields);

    if (count($mutationFields)) {
      return new ObjectType([
        'name' => 'Mutation',
        'fields' => $mutationFields,
      ]);
    }

    return null;
  }
}