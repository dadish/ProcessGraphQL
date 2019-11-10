<?php

namespace ProcessWire\GraphQL\Test;

use PHPUnit\Framework\TestCase;
use ProcessWire\GraphQL\Utils;
use ProcessWire\GraphQL\Schema;
use ProcessWire\Template;
use ProcessWire\Field;

abstract class GraphqlTestCase extends TestCase {

  const settings = [];

  private static $defaultConfig;
  private static $originalAccessRules = [];

  public static function rememberOriginalAccessRules($accessRules)
  {
    $originals = [];
    if (isset($accessRules['templates'])) {
      $originals['templates'] = [];
      foreach ($accessRules['templates'] as $rules) {
        if (!isset($rules['name'])) {
          throw new \Error("template rule should have a name. E.g. 'name' => 'templateName'.");
        }
        $templateName = $rules['name'];
        $template = Utils::templates()->get("name=$templateName");
        if (!$template instanceof Template) {
          throw new \Error("'$templateName' is not a valid template.");
        }
        $originalRules = [];
        $originalRules['useRoles'] = $template->useRoles;
        foreach ($rules as $property => $value) {
          if ($property === 'name') {
            $originalRules['name'] = $value;
          } else {
            $originalRules[$property] = $template->$property;
          }
        }
        $originals['templates'][] = $originalRules;
      }
    }

    if (isset($accessRules['fields'])) {
      $originals['fields'] = [];
      foreach ($accessRules['fields'] as $rules) {
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
        $originalRules = [];
        $originalRules['useRoles'] = $field->useRoles;
        foreach ($rules as $property => $value) {
          if (in_array($property, ['name', 'context'])) {
            $originalRules[$property] = $value;
          } else {
            $originalRules[$property] = $field->$property;
          }
        }
        $originals['fields'][] = $originalRules;
      }
    }

    if (isset($accessRules['roles'])) {
      $originals['roles'] = [];
      foreach ($accessRules['roles'] as $rules) {
        if (!isset($rules['name'])) {
          throw new \Error("role rule should have a name. E.g. 'name' => 'roleName'.");
        }
        $role = Utils::roles()->get($rules['name']);
        $originals['roles'][] = [
          'name' => $rules['name'],
          'permissions' => $role->permissions->explode('name')
        ];
      }
    }

    if (isset($accessRules['permissions'])) {
      $originals['permissions'] = Utils::permissions()->find("name!=0")->explode('name');
    }

    self::$originalAccessRules = $originals;
    return $originals;
  }

  public static function setUpBeforeClass()
  {
    // get settings
    $self = static::class;
    $settings = $self::settings;

    // if settings are empty then try to populate
    // them via getSettings method
    if (!count($settings) && method_exists($self, 'getSettings')) {
      $settings = $self::getSettings();
    }

    // cache the original rules
    self::$defaultConfig = Utils::module()->data();

    if (isset($settings['legalTemplates'])) {
      Utils::module()->legalTemplates = array_merge(Utils::module()->legalTemplates, $settings['legalTemplates']);
    }
    
    if (isset($settings['legalFields'])) {
      Utils::module()->legalFields = array_merge(Utils::module()->legalFields, $settings['legalFields']);
    }
    
    if (isset($settings['legalPageFields'])) {
      Utils::module()->legalPageFields = array_merge(Utils::module()->legalPageFields, $settings['legalPageFields']);
    }
    
    if (isset($settings['legalPageFileFields'])) {
      Utils::module()->legalPageFileFields = array_merge(Utils::module()->legalPageFileFields, $settings['legalPageFileFields']);
    }
    
    if (isset($settings['legalPageImageFields'])) {
      Utils::module()->legalPageImageFields = array_merge(Utils::module()->legalPageImageFields, $settings['legalPageImageFields']);
    }

    if (isset($settings['access'])) {
      self::rememberOriginalAccessRules($settings['access']);

      if (isset($settings['access']['permissions'])) {
        $permissions = $settings['access']['permissions'];
        if (isset($permissions['add'])) {
          foreach ($permissions['add'] as $permissionName) {
            $permission = Utils::permissions()->get($permissionName);
            if ($permission->id) {
              continue;
            }
            $permission = Utils::permissions()->add($permissionName);
            $permission->title = $permissionName;
            $permission->save();
          }
        }
        if (isset($permissions['remove'])) {
          foreach ($permissions['remove'] as $permissionName) {
            $permission = Utils::permissions()->get($permissionName);
            if (!$permission->id) {
              continue;
            }
            Utils::permissions()->delete($permission);
          }
        }
      }

      if (isset($settings['access']['roles'])) {
        foreach ($settings['access']['roles'] as $rules) {
          $role = Utils::roles()->get($rules['name']);
          foreach ($rules as $action => $permissions) {
            if (!in_array($action, ['add', 'remove'])) {
              continue;
            }
            $method = $action . 'Permission';
            foreach ($permissions as $permission) {
              $role->$method($permission);
            }
          }
        }
      }

      if (isset($settings['access']['templates'])) {
        foreach ($settings['access']['templates'] as $rules) {
          $templateName = $rules['name'];
          $template = Utils::templates()->get("name=$templateName");
          if (
            isset($rules['roles']) ||
            isset($rules['editRoles']) ||
            isset($rules['addRoles']) ||
            isset($rules['createRoles'])
          ) {
            $template->useRoles = 1;
          }
          foreach ($rules as $property => $value) {
            if ($property === 'name') {
              continue;
            }
            $template->$property = $value;
          }
        }
      }

      if (isset($settings['access']['fields'])) {
        foreach ($settings['access']['fields'] as $rules) {
          $fieldName = $rules['name'];
          $field = Utils::fields()->get("name=$fieldName");
          if (isset($rules['context'])) {
            $template = Utils::templates()->get($rules['context']);
            $field = $template->fieldgroup->getFieldContext($field);
          }
          if (isset($rules['editRoles']) || isset($rules['viewRoles'])) {
            $field->useRoles = true;
          }
          foreach ($rules as $property => $value) {
            if (in_array($property, ['name', 'context'])) {
              continue;
            }
            $field->$property = $value;
          }
          if (isset($rules['context']) && $template instanceof Template) {
            Utils::fields()->saveFieldgroupContext($field, $template->fieldgroup);
          }
        }
      }
    }

    if (isset($settings['login'])) {
      $username = $settings['login'];
      Utils::session()->login($username, Utils::config()->testUsers[$username]);
    }
  }

  public static function tearDownAfterClass()
  {
    Utils::session()->logout();
    Utils::module()->setArray(self::$defaultConfig);

    // get settings
    $self = static::class;
    $settings = $self::settings;

    // if settings are empty then try to populate
    // them via getSettings method
    if (!count($settings) && method_exists($self, 'getSettings')) {
      $settings = $self::getSettings();
    }
    if (!isset($settings['access'])) {
      return;
    }
    $accessRules = self::$originalAccessRules;
    if (isset($accessRules['templates'])) {
      foreach ($accessRules['templates'] as $rules) {
        $templateName = $rules['name'];
        $template = Utils::templates()->get("name=$templateName");
        foreach ($rules as $property => $value) {
          if (in_array($property, ['name', 'useRoles'])) {
            continue;
          }
          $template->$property = $value;
        }
        $template->useRoles = $rules['useRoles'];
      }
    }

    if (isset($accessRules['fields'])) {
      foreach ($accessRules['fields'] as $rules) {
        $fieldName = $rules['name'];
        $field = Utils::fields()->get("name=$fieldName");
        if (isset($rules['context'])) {
          $template = Utils::templates()->get($rules['context']);
          $field = $template->fieldgroup->getFieldContext($field);
        }
        foreach ($rules as $property => $value) {
          if (in_array($property, ['name', 'context', 'useRoles'])) {
            continue;
          }
          $field->$property = $value;
        }
        $field->useRoles = $rules['useRoles'];
        if (isset($rules['context']) && $template instanceof Template) {
          Utils::fields()->saveFieldgroupContext($field, $template->fieldgroup);
        }
      }
    }

    if (isset($accessRules['roles'])) {
      foreach ($accessRules['roles'] as $rules) {
        $role = Utils::roles()->get($rules['name']);
        // remove all existing permissions
        foreach ($role->permissions as $permission) {
          $role->removePermission($permission);
        }
        // add all original permissions
        foreach ($rules['permissions'] as $permission) {
          $role->addPermission($permission);
        }
      }
    }

    if (isset($accessRules['permissions'])) {
      // remove all permissions that were not in the
      // original permissions list
      $oldPermissions = implode('|', $accessRules['permissions']);
      $newPermissions = Utils::permissions()->find("name!=$oldPermissions");
      foreach ($newPermissions as $permission) {
        Utils::permissions()->delete($permission);
      }

      // install back the permission if it is in the original list
      // but not installed
      foreach ($accessRules['permissions'] as $permissionName) {
        $permission = Utils::permissions()->get($permissionName);
        if ($permission->id) {
          continue;
        }
        $permission = Utils::permissions()->add($permissionName);
        $permission->title = $permissionName;
        $permission->save();
      }
    }
  }

  public static function execute($payload = null, $variables = null)
  {
    Schema::build();
    $res = Utils::module()->executeGraphQL($payload, $variables);
    return json_decode(json_encode($res), false);
  }
}