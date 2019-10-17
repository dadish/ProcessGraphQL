<?php

namespace ProcessWire\GraphQL\Test;

use PHPUnit\Framework\TestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Schema;

abstract class GraphqlTestCase extends TestCase {

  const accessRules = [];

  public static $defaultConfig;

  public static function setUpBeforeClass()
  {
    $self = static::class;
    $accessRules = $self::accessRules;
    self::$defaultConfig = Utils::module()->data();
    if (isset($accessRules['legalTemplates'])) {
      Utils::module()->legalTemplates = array_merge(Utils::module()->legalTemplates, $accessRules['legalTemplates']);
    }
    
    if (isset($accessRules['legalFields'])) {
      Utils::module()->legalFields = array_merge(Utils::module()->legalFields, $accessRules['legalFields']);
    }
    
    if (isset($accessRules['legalPageFields'])) {
      Utils::module()->legalPageFields = array_merge(Utils::module()->legalPageFields, $accessRules['legalPageFields']);
    }
    
    if (isset($accessRules['legalPageFileFields'])) {
      Utils::module()->legalPageFileFields = array_merge(Utils::module()->legalPageFileFields, $accessRules['legalPageFileFields']);
    }
    
    if (isset($accessRules['legalPageImageFields'])) {
      Utils::module()->legalPageImageFields = array_merge(Utils::module()->legalPageImageFields, $accessRules['legalPageImageFields']);
    }

    if (count($accessRules)) {
      Utils::session()->login('admin', Utils::config()->testUsers['admin']);
    }
  }

  public static function tearDownAfterClass()
  {
    Utils::session()->logout();
    Utils::module()->setArray(self::$defaultConfig);
  }

  public static function execute($payload = null, $variables = null)
  {
    Schema::build();
  	return Utils::module()->executeGraphQL($payload, $variables);
  }

}