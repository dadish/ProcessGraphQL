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
      case 'legalFields':
        return $this->getLegalFields();
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

    return $templates;
  }

  protected function getLegalFields()
  {
    $legalFields = $this->module->legalFields;
    return Utils::fields()->getAll()->find("name=" . implode('|', $legalFields));
  }

}
