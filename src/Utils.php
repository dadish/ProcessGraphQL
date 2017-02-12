<?php

/**
 * Serves the same purpose as ProcessWire's Functions.php
 * This one here is in case we need to overwrite some functions
 * or add some more
 */

namespace ProcessWire\GraphQL;

use ProcessWire\GraphQL\Config;

class Utils {

 /**
  * Same as ProcessWire wire function.
  * @param  string $name The name of the api. E.g. `pages`, `modules`, `templates`.
  * @return mixed       Returns the ProcessWire API variable.
  */
  static function wire($name='wire')
  {
    return \ProcessWire\wire($name);
  }

 /**
  * Shortcut for wire('user')
  * @return \ProcessWire\User The ProcessWire $user API variable.
  */
  static function user()
  {
    return self::wire('user');
  }

 /**
  * Shortcut for wire('users')
  * @return \ProcessWire\Users The ProcessWire $users variable.
  */
  static function users()
  {
    return self::wire('users');
  }

 /**
  * Shortcut for wire('modules')
  * @return \ProcessWire\Modules The ProcessWire $modules API variable
  */
  static function modules()
  {
    return self::wire('modules');
  }

 /**
  * Shortcut for wire('fields')
  * @return \ProcessWire\Fields The ProcessWire $fields API variable.
  */
  static function fields()
  {
    return self::wire('fields');
  }

 /**
  * Shortcut for wire('sanitizer')
  * @return \ProcessWire\Sanitizer The ProcessWire $sanitizer variable.
  */
  static function sanitizer()
  {
    return self::wire('sanitizer');
  }

 /**
  * Shortcut for wire('config')
  * @return \ProcessWire\Config The ProcessWire $config API variable.
  */
  static function config()
  {
    return self::wire('config');
  }

 /**
  * Shortcut for wire('session')
  * @return \ProcessWire\session The ProcessWire $session variable.
  */
  static function session()
  {
    return self::wire('session');
  }

 /**
  * Shortcut for wire('users')
  * @return \ProcessWire\Users The ProcessWire $users variabe.
  */
  static function roles()
  {
    return self::wire('roles');
  }

 /**
  * Shortcut for wire('permissions')
  * @return \ProcessWire\Permissions The ProcessWire $permissions variable.
  */
  static function permissions()
  {
    return self::wire('permissions');
  }

 /**
  * Returns the ProcessGraphQL module instance.
  * @return \ProcessWire\ProcessGraphQL The ProcessGraphQL module instance
  */
  static function module()
  {
    return self::modules()->get('ProcessGraphQL');
  }

 /**
  * Creates and sets the Config property to ProcessGraphQL module instance.
  *
  * @return \ProcessWire\GraphQL\Config The ProcessGaphQL module runtime configuration
  */
  static function setupModuleConfig()
  {
    $module = self::module();
    $config = new Config($module);
    return $module->Config;
  }

  /**
   * Returns ProcessGraphQL's runtime configuration
   *
   * @return \ProcessWire\GraphQL\Config
   *
   */
  static function moduleConfig()
  {
    $module = self::module();
    if ($module->Config instanceof Config) return $module->Config;
    return self::setupModuleConfig();
  }

 /**
  * An array of reserved words for future use
  * @return array Array of strings.
  */
  static function getReservedWords()
  {
    return [
      'me', 'debug', 'login', 'logout',
      'pages','templates','template','fields',
      'roles', 'permissions', 'config', 'system',
      'wire', 'enum', 'trash', 'users', 'setup',
      'modules', 'access', 'find', 'logs', 'site',
      'core',
    ];
  }

}
