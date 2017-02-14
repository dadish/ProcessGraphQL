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
        return $this->module->$key;
      case 'legalViewTemplates':
        if ($super) return $this->getLegalTemplates();
        return $this->getLegalTemplatesForPermission('page-view');
      case 'legalCreateTemplates':
        if ($super) return $this->getLegalTemplates();
        return $this->getLegalTemplatesForPermission('page-create');
      case 'legalEditTemplates':
        if ($super) return $this->getLegalTemplates();
        return $this->getLegalTemplatesForPermission('page-edit');
      case 'legalViewFields':
        if ($super) return $this->getLegalFields();
        return $this->getLegalFieldsForPermission('view');
      case 'legalEditFields':
        if ($super) return $this->getLegalFields();
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
    $templates = $this->getLegalTemplates()->find("useRoles=1");
    foreach ($templates as $template) {
      if (!Utils::user()->hasTemplatePermission($permission, $template)) {
        $templates->remove($template);
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
    $fields = $this->getLegalFields()->find("useRoles=1");
    $rolesType = $permission . "Roles";
    foreach ($fields as $field) {
      if (!$this->userHasPermission($field->$rolesType)) {
        $fields->remove($field);
      }
    }
    return $fields;
  }

  protected function userHasPermission($rolesID)
  {
    $userRolesID = Utils::user()->roles->explode('id');
    foreach ($userRolesID as $userRoleID) {
      if (in_array($userRoleID, $rolesID)) return true;
    }
    return false;
  }

}
