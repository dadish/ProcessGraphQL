<?php

/**
 * Serves the same purpose as ProcessWire's Functions.php
 * This one here is in case we need to overwrite some functions
 * or add some more
 */

namespace ProcessWire\GraphQL;

use ProcessWire\Languages;
use ProcessWire\Field;
use ProcessWire\Template;
use ProcessWire\GraphQL\Config;

class Utils {

 /**
  * Same as ProcessWire wire function.
  * @param  string $name The name of the api. E.g. `pages`, `modules`, `templates`.
  * @return mixed       Returns the ProcessWire API variable.
  */
  public static function wire($name='wire')
  {
    return \ProcessWire\wire($name);
  }

  /**
   * Shorcut for wire('pages')
   * @return \ProcessWire\Pages The ProcessWire $pages API variable.
   */
  public static function pages()
  {
    return self::wire('pages');
  }

 /**
  * Shortcut for wire('user')
  * @return \ProcessWire\User The ProcessWire $user API variable.
  */
  public static function user()
  {
    return self::wire('user');
  }

 /**
  * Shortcut for wire('users')
  * @return \ProcessWire\Users The ProcessWire $users variable.
  */
  public static function users()
  {
    return self::wire('users');
  }

 /**
  * Shortcut for wire('modules')
  * @return \ProcessWire\Modules The ProcessWire $modules API variable
  */
  public static function modules()
  {
    return self::wire('modules');
  }

 /**
  * Shortcut for wire('templates')
  * @return \ProcessWire\Fields The ProcessWire $templates API variable.
  */
  public static function templates()
  {
    return self::wire('templates');
  }

 /**
  * Shortcut for wire('fields')
  * @return \ProcessWire\Fields The ProcessWire $fields API variable.
  */
  public static function fields()
  {
    return self::wire('fields');
  }

 /**
  * Shortcut for wire('sanitizer')
  * @return \ProcessWire\Sanitizer The ProcessWire $sanitizer variable.
  */
  public static function sanitizer()
  {
    return self::wire('sanitizer');
  }

 /**
  * Shortcut for wire('config')
  * @return \ProcessWire\Config The ProcessWire $config API variable.
  */
  public static function config()
  {
    return self::wire('config');
  }

 /**
  * Shortcut for wire('session')
  * @return \ProcessWire\session The ProcessWire $session variable.
  */
  public static function session()
  {
    return self::wire('session');
  }

 /**
  * Shortcut for wire('users')
  * @return \ProcessWire\Users The ProcessWire $users variabe.
  */
  public static function roles()
  {
    return self::wire('roles');
  }

 /**
  * Shortcut for wire('permissions')
  * @return \ProcessWire\Permissions The ProcessWire $permissions variable.
  */
  public static function permissions()
  {
    return self::wire('permissions');
  }

 /**
  * Returns the ProcessGraphQL module instance.
  * @return \ProcessWire\ProcessGraphQL The ProcessGraphQL module instance
  */
  public static function module()
  {
    return self::modules()->get('ProcessGraphQL');
  }

  /**
   * Shortcut for wire('languages')
   * @return ProcessWire\Languages The ProcessWire $languages API variable.
   */
  public static function languages()
  {
    return self::wire('languages');
  }

 /**
  * Creates and sets the Config property to ProcessGraphQL module instance.
  *
  * @return \ProcessWire\GraphQL\Config The ProcessGaphQL module runtime configuration
  */
  public static function setupModuleConfig()
  {
    $module = self::module();
    $config = new Config($module);
    self::setupModuleLanguageConfig($config);
    return $module->Config;
  }

  /**
   * Sets the `languageEnabled` property for Config
   * @param  Config $config The ProcessGraphQL runtime configuration
   * @return boolean        Returns true if LanguageSupport is enabled, false otherwise.
   */
  public static function setupModuleLanguageConfig(Config $config)
  {
    $languages = self::languages();
    if (is_null($languages) || !$languages instanceof Languages) {
      $config->languageEnabled = false;
      return false;
    }
    $config->languageEnabled = true;
    return true;
  }

  /**
   * Returns ProcessGraphQL's runtime configuration
   *
   * @return \ProcessWire\GraphQL\Config
   *
   */
  public static function moduleConfig()
  {
    $module = self::module();
    if ($module->Config instanceof Config) return $module->Config;
    return self::setupModuleConfig();
  }

 /**
  * An array of reserved words for future use
  * @return array Array of strings.
  */
  public static function getReservedWords()
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

  /**
   * Determines whether the current user has given permission on $field within
   * $template's context.
   * @param  string   $permission The permission type. Either 'view' or 'edit'
   * @param  Field    $field      The field against the check is performed
   * @param  Template $template   The context of the field.
   * @return boolean              Returns true if user has rights and false otherwise
   */
  public static function hasFieldPermission($permission = 'view', Field $field, Template $template)
  {
    $user = self::user();
    if ($user->isSuperuser()) return true;
    $field = $template->fields->getFieldContext($field);
    if ($field->useRoles) {
      $roles = $permission . 'Roles';
      foreach ($user->roles as $role) {
        if (in_array($role->id, $field->$roles)) return true;
      }
      return false;
    } else if (self::moduleConfig()->grantFieldsAccess) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Matches a ProcessWire Fieldtype to corresponding GraphQL Field
   * @param Field $field A Processwire field.
   * @return mixed|null Returns a GraphQL compatible field or null if match is not found.
   */
  public static function pwFieldToGraphqlClass(Field $field)
  {
    ChromePhp::log();
    // use local field if available
    $className = "\\ProcessWire\\GraphQL\\Type\\Fieldtype\\" . $field->type->className();
    if (class_exists($className)) {
      return $className;
    }

    // use third party field if available
    // $thirdPartyClassName = "\\ProcessWire\\GraphQL" . $field->type->className();
    // if (class_exists($thirdPartyClassName)) {
    //   return new FieldtypeThirdParty($thirdPartyClassName, $field);
    // }

    return null;
  }

  public static function getTemplateCacheKey(Template $template)
  {
    $key = '';
    $seperator = '-';

    // user's roles
    $key .= Utils::user()->roles->implode($seperator, 'name');
    
    // template name
    $key .= "$seperator$seperator";
    $key .= $template->name;
    
    // allowed fields
    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalFields);
    
    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageFields);

    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageFileFields);

    $key .= "$seperator$seperator";
    $key .= implode($seperator, Utils::moduleConfig()->legalPageImageFields);

    return $key;
  }
}
