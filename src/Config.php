<?php

namespace ProcessWire\GraphQL;

use ProcessWire\WireData;
use ProcessWire\ProcessGraphQL;

class Config extends WireData {

  protected $module;

  public function __construct(ProcessGraphQL $module)
  {
    $this->module = $module;

    // cache reference for useful ProcessWire API variables
    $apiVars = [
      'templates',
      'fields',
      'roles',
      'users',
      'pages',
    ];
    foreach ($apiVars as $varName) {
      $this->$varName = \ProcessWire\wire($varName);
    }

    // Assign Config to module so we can access it easily accross the module codebase.
    $module->Config = $this;
  }

  public function get($key)
  {
    switch ($key) {
      case 'maxLimit':
      case 'fullWidthGraphiQL':
      case 'legalPageFields':
      case 'legalPageFileFields':
        return $this->module->$key;
      case 'legalTemplates':
        return $this->getLegalTemplates();
      case 'legalFields':
        return $this->getLegalFields();
      default:
        return parent::get($key);
    }
  }

  protected function getLegalTemplates()
  {
    $legalTemplates = $this->module->legalTemplates;
    $templates = \ProcessWire\wire('templates')->find("name=" . implode('|', $legalTemplates));
    $user = \ProcessWire\wire('user');

    // Wierd behavior with ProcessWire. $user->hasPermission() does not
    // work if you do not load the required roles beforehand.
    \ProcessWire\wire('roles')->find("");

    foreach ($templates as $template) {
      // We serve only those that user has permission to view
      if (!$user->hasTemplatePermission('page-view', $template)) {
        $templates->remove($template);
      }
    }

    return $templates;
  }

  protected function getLegalFields()
  {
    $legalFields = $this->module->legalFields;
    $fields = \ProcessWire\wire('fields')->find("name=" . implode('|', $legalFields));
    return $fields;
  }

}
