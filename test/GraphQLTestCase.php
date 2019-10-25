<?php

namespace ProcessWire\GraphQL\Test;

use PHPUnit\Framework\TestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Schema;
use ProcessWire\Template;
use ProcessWire\Field;

abstract class GraphqlTestCase extends TestCase {

  const accessRules = [];

  const introspectionQuery = "{
    __schema {
      types {
        ... on __Type {
          kind
          name
          fields {
            name
          }
        }
      }
    }
  }";

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
        foreach ($accessRules['access']['templates'] as $rules) {
          if (!isset($rules['name'])) {
            throw new \Error("template rule should have a name. E.g. 'name' => 'templateName'.");
          }
          $templateName = $rules['name'];
          $template = Utils::templates()->get("name=$templateName");
          if (!$template instanceof Template) {
            throw new \Error("'$templateName' is not a valid template.");
          }
          $template->useRoles = 1;
          foreach ($rules as $type => $roles) {
            if ($type === 'name') {
              continue;
            }
            $template->setRoles($roles, $type);
          }
        }
      }

      if (isset($accessRules['access']['fields'])) {
        foreach ($accessRules['access']['fields'] as $rules) {
          if (!isset($rules['name'])) {
            throw new \Error("field rule should have a name. E.g. 'name' => 'fieldName'.");
          }
          $fieldName = $rules['name'];
          $field = Utils::fields()->get("name=$fieldName");
          if (isset($rules['context'])) {
            $template = Utils::templates()->get($rules['context']);
            $field = $template->fieldgroup->getFieldContext($field);
          }
          if (!$field instanceof Field) {
            throw new \Error("'$fieldName' is not a valid field.");
          }
          $field->useRoles = 1;
          foreach ($rules as $type => $roles) {
            if (in_array($type, ['name', 'context'])) {
              continue;
            }
            $field->setRoles($type, $roles);
          }
          if (isset($rules['context']) && $template instanceof Template) {
            Utils::fields()->saveFieldgroupContext($field, $template->fieldgroup);
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
        foreach ($accessRules['access']['templates'] as $rules) {
          $templateName = $rules['name'];
          $template = Utils::templates()->get("name=$templateName");
          foreach ($rules as $type => $roles) {
            if ($type === 'name') {
              continue;
            }
            $template->setRoles([], $type);
          }
          $template->useRoles = 0;
        }
      }

      if (isset($accessRules['access']['fields'])) {
        foreach ($accessRules['access']['fields'] as $rules) {
          $fieldName = $rules['name'];
          $field = Utils::fields()->get("name=$fieldName");
          if (isset($rules['context'])) {
            $template = Utils::templates()->get($rules['context']);
            $field = $template->fieldgroup->getFieldContext($field);
          }
          foreach ($rules as $type => $roles) {
            if (in_array($type, ['name', 'context'])) {
              continue;
            }
            $field->setRoles($type, []);
          }
          $field->useRoles = 0;
          if (isset($rules['context']) && $template instanceof Template) {
            Utils::fields()->saveFieldgroupContext($field, $template->fieldgroup);
          }
        }
      }
    }
  }

  public static function execute($payload = null, $variables = null)
  {
    Schema::build();
    $res = Utils::module()->executeGraphQL($payload, $variables);
    return json_decode(json_encode($res), false);
  }

  public static function selectByProperty($arr, $property, $value)
  {
    foreach ($arr as $item) {
      if ($item->$property === $value) {
        return $item;
      }
    }
    return null;
  }

}