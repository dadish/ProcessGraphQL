<?php

namespace ProcessWire\GraphQL;

use ProcessWire\WireData;
use ProcessWire\ProcessGraphQL;
use ProcessWire\GraphQL\Utils;

class Config extends WireData {

  protected $module;

  public function __construct(ProcessGraphQL $module)
  {
    $this->module = $module;

    // Assign Config to module so we can access it easily accross the module codebase.
    $module->Config = $this;

    // Wierd behavior with ProcessWire. $user->hasPermission() does not
    // work if you do not load the required roles beforehand.
    Utils::roles()->find("");
  }

  public function get($key)
  {
    $super = Utils::user()->isSuperuser();
    switch ($key) {
      case 'maxLimit':
      case 'fullWidthGraphiQL':
      case 'legalPageFields':
      case 'legalPageFileFields':
      case 'grantTemplatesAccess':
      case 'grantFieldsAccess':
      case 'pagesQuery':
      case 'meQuery':
      case 'authQuery':
        return $this->module->$key;
      case 'legalViewTemplates':
        return $this->getLegalTemplatesForPermission('page-view');
      case 'legalCreateTemplates':
        return $this->getLegalTemplatesForPermission('page-create');
      case 'legalEditTemplates':
        return $this->getLegalTemplatesForPermission('page-edit');
      case 'legalViewFields':
        return $this->getLegalFieldsForPermission('view');
      case 'legalEditFields':
        return $this->getLegalFieldsForPermission('edit');
      default:
        return parent::get($key);
    }
  }

  protected function getLegalTemplates()
  {
    $legalTemplates = $this->module->legalTemplates;
    return Utils::templates()->find("name=" . implode('|', $legalTemplates));
  }

  protected function getLegalTemplatesForPermission($permission = 'page-view')
  {
    $user = Utils::user();
    $templates = $this->getLegalTemplates();

    // if superuser give access to everything
    if ($user->isSuperuser()) return $templates;

    // if access is granted then templates are accessable by default
    // but if a template has Access settings, user should have relevant
    // permissions
    if (Utils::moduleConfig()->grantTemplateAccess) {
      foreach ($templates as $template) {
        if ($template->useRoles && !$user->hasTemplatePermission($permission, $template)) {
          $templates->remove($template);
        }
      }

    // if access is not granted then user can see only those templates that
    // she has explicit access to.
    } else {
      $templates->filter("useRoles=1");
      foreach ($templates as $template) {
        if (!$user->hasTemplatePermission($permission, $template)) {
          $templates->remove($template);
        }
      }
    }

    return $templates;
  }

  protected function getLegalFields()
  {
    $legalFields = $this->module->legalFields;
    return Utils::fields()->find("name=" . implode('|', $legalFields));
  }

  protected function getLegalFieldsForPermission($permission = 'view')
  {
    $fields = $this->getLegalFields();
    $roles = $permission . "Roles";

    // if superuser give access to everything
    if (Utils::user()->isSuperuser()) return $fields;

    if (Utils::moduleConfig()->grantFieldAccess) {
      foreach ($fields as $field) {
        if ($field->useRoles && !$this->userHasRoleIn($field->$roles)) {
          $fields->remove($field);
        }
      }
    } else {
      $fields->find("useRoles=1");
      foreach ($fields as $field) {
        if (!$this->userHasRoleIn($field->$roles)) {
          $fields->remove($field);
        }
      }
    }

    return $fields;
  }

  protected function userHasRoleIn($rolesID)
  {
    $userRolesID = Utils::user()->roles->explode('id');
    foreach ($userRolesID as $userRoleID) {
      if (in_array($userRoleID, $rolesID)) return true;
    }
    return false;
  }

}
