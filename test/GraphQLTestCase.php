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
    // get accessRules
    $self = static::class;
    $accessRules = $self::accessRules;

    // if accessRules are empty then try to populate
    // them via getAccessRules methof
    if (!count($accessRules) && method_exists($self, 'getAccessRules')) {
      $accessRules = $self::getAccessRules();
    }

    // cache the original rules
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

    if (isset($accessRules['login'])) {
      $username = $accessRules['login'];
      Utils::session()->login($username, Utils::config()->testUsers[$username]);
    }

    if (isset($accessRules['access'])) {
      if (isset($accessRules['access']['templates'])) {
        foreach ($accessRules['access']['templates'] as $templateName => $rules) {
          $template = Utils::templates()->get("name=$templateName");
          $template->useRoles = 1;
          foreach ($rules as $type => $roles) {
            $template->setRoles($roles, $type);
          }
        }
      }

      if (isset($accessRules['access']['fields'])) {
        foreach ($accessRules['access']['fields'] as $fieldName => $rules) {
          $field = Utils::fields()->get("name=$fieldName");
          $field->useRoles = 1;
          foreach ($rules as $type => $roles) {
            $field->setRoles($type, $roles);
          }
        }
      }
    }
  }

  public static function tearDownAfterClass()
  {
    Utils::session()->logout();
    Utils::module()->setArray(self::$defaultConfig);

    // get accessRules
    $self = static::class;
    $accessRules = $self::accessRules;

    // if accessRules are empty then try to populate
    // them via getAccessRules methof
    if (!count($accessRules) && method_exists($self, 'getAccessRules')) {
      $accessRules = $self::getAccessRules();
    }

    if (isset($accessRules['access'])) {
      if (isset($accessRules['access']['templates'])) {
        foreach ($accessRules['access']['templates'] as $templateName => $rules) {
          $template = Utils::templates()->get("name=$templateName");
          foreach ($rules as $type => $roles) {
            $template->setRoles([], $type);
          }
          $template->useRoles = 0;
        }
      }

      if (isset($accessRules['access']['fields'])) {
        foreach ($accessRules['access']['fields'] as $fieldName => $rules) {
          $field = Utils::fields()->get("name=$fieldName");
          foreach ($rules as $type => $roles) {
            $field->setRoles($type, []);
          }
          $field->useRoles = 0;
        }
      }
    }
  }

  public static function execute($payload = null, $variables = null)
  {
    Schema::build();
  	return Utils::module()->executeGraphQL($payload, $variables);
  }

}