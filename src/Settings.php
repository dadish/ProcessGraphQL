<?php

namespace ProcessWire\GraphQL;

class Settings {

  public static function module()
  {
    return \Processwire\wire('modules')->get('ProcessGraphQL');
  }

  public static function getLegalTemplates()
  {
    $templates = \ProcessWire\wire('templates')->getAll();
    $user = \ProcessWire\wire('user');
    $includeSystems = (boolean) self::module()->includeSystemTemplates;
    $viewableTemplateNames = [];
    foreach ($templates as $template) {
      if (!$user->hasPermission('page-view', $template)) continue;
      $viewableTemplateNames[] = $template->name;
    }
    if (count($viewableTemplateNames)) $templates = $templates->filter("name=" . implode('|', $viewableTemplateNames));
    if (!$includeSystems) $templates = $templates->filter("flags!=8");
    return $templates;
  }

}