<?php

namespace ProcessWire\GraphQL;

use ProcessWire\WireData;
use ProcessWire\ProcessGraphQL;
use ProcessWire\Fieldgroup;
use ProcessWire\GraphQL\Utils;

class Config extends WireData {

  /**
   * Shortcut for our ProcessGraphQL module
   * @var \ProcessWire\ProcessGraphQL
   */
  protected $module;

  /**
   * Maps permission names into template access roles properties.
   * @var array
   */
  protected $permissionToRoles = [
    'page-view' => 'roles',
    'page-edit' => 'editRoles',
    'page-add' => 'addRoles',
    'page-create' => 'createRoles',
  ];

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
      case 'legalFields':
      case 'legalPageFields':
      case 'legalPageFileFields':
      case 'legalPageImageFields':
      case 'grantTemplatesAccess':
      case 'grantFieldsAccess':
      case 'pagesQuery':
      case 'meQuery':
      case 'authQuery':
        return $this->module->$key;
      case 'legalViewTemplates':
        return $this->getLegalTemplatesForPermission('page-view');
      case 'legalCreateTemplates':
        return $this->getLegalCreateTemplates();
      case 'legalEditTemplates':
        return $this->getLegalTemplatesForPermission('page-edit');
      case 'legalAddTemplates':
        return $this->getLegalTemplatesForPermission('page-add');
      default:
        return parent::get($key);
    }
  }

  protected function getLegalTemplates()
  {
    $legalTemplates = $this->module->legalTemplates;
    return Utils::templates()->getAll()->find("name=" . implode('|', $legalTemplates));
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
    if (Utils::moduleConfig()->grantTemplatesAccess) {
      foreach ($templates as $template) {
        if ($template->useRoles && !$this->hasTemplatePermission($permission, $user, $template)) {
          $templates->remove($template);
        }
      }

    // if access is not granted then user can see only those templates that
    // she has explicit access to.
    } else {
      $templates->filter("useRoles=1");
      foreach ($templates as $template) {
        if (!$this->hasTemplatePermission($permission, $user, $template)) {
          $templates->remove($template);
        }
      }
    }

    return $templates;
  }

  /**
   * Checks if the user has a particular permission on the given template
   * @param  string   $name     The name of the permission. E.g. 'page-view', 'page-add'.
   * @param  User     $user     The ProcessWire User
   * @param  Template $template The ProcessWire Template
   * @return boolean            Returns true if user has a permission on the target template and false otherwise
   */
  protected function hasTemplatePermission($name, \ProcessWire\User $user, \ProcessWire\Template $template)
  {
    $rolesName = $this->permissionToRoles[$name];
    $templateRoles = $template->$rolesName;
    if ($name === 'page-view') $templateRoles = $templateRoles->explode('id');
    foreach ($user->roles as $role) {
      if (in_array($role->id, $templateRoles)) return true;
    }
    return false;
  }

  /**
   * Page cannot be created without it's required field
   * populated with value. Therefore only templates that
   * has all required fields as legal will be allowed for
   * create operation
   * @return TemplatesArray Templates that are legal for create operation
   */
  protected function getLegalCreateTemplates() {
    $templates = $this->getLegalTemplatesForPermission('page-create');
    $legalFields = Utils::module()->legalFields;

    // go over each templates
    foreach ($templates as $template) {
      // get the required fields of each template
      foreach ($template->fields->find("required=1") as $field) {
        // check if the required field of the tempate is legal
        if (!in_array($field->name, $legalFields)) {
          // if not legal then the page associeated with this template cannot be created
          // remove the template & stop checking rest of the fields of this template
          $templates->remove($template);
          break;
        }
      }
    }

    // prevent creation of pages without required fields
    foreach ($templates as $template) {
      if (!self::allFieldsAreLegal($template->fields->find("required=1"))) {
        $templates->remove($template);
      }
    }

    return $templates;
  }

  public static function allFieldsAreLegal(Fieldgroup $fields)
  {
    $legalFields = Utils::moduleConfig()->legalFields;
    foreach ($fields as $field) {
      if (!in_array($field->name, $legalFields)) return false;
    }
    return true;
  }

}
