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
    
    foreach ($templates as $template) {
      if (!$user->hasPermission('page-view', $template)) {
        $templates->remove($template);
      }
    }

    return $templates;
  }

}