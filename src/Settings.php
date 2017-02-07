<?php

namespace ProcessWire\GraphQL;

class Settings {

  public static function module()
  {
    return \Processwire\wire('modules')->get('ProcessGraphQL');
  }

  public static function getLegalTemplates()
  {
    $legalTemplates = self::module()->legalTemplates;
    $templates = \ProcessWire\wire('templates')->find("name=" . implode('|', $legalTemplates));
    $user = \ProcessWire\wire('user');

    // Wierd behavior with ProcessWire. $user->hasPermission() does not
    // work if you do not load the required roles beforehand.
    \ProcessWire\wire('roles')->find("");
    
    foreach ($templates as $template) {
      // we serve templates with access control disabled
      if (!$template->useRoles) continue;

      // if enabled we serve only those that user has permission to view
      if (!$user->hasTemplatePermission('page-view', $template)) {
        $templates->remove($template);
      }
    }

    return $templates;
  }

  public static function getLegalFields()
  {
    $legalFields = self::module()->legalFields;
    $fields = \ProcessWire\wire('fields')->find("name=" . implode('|', $legalFields));
    return $fields;
  }

  public static function getLegalPageFields()
  {
    return self::module()->legalPageFields;
  }

}