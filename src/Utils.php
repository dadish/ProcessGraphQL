<?php

/**
 * Serves the same purpose as ProcessWire's Functions.php
 * This one here is in case we need to overwrite some functions
 * or add some more
 */

namespace ProcessWire\GraphQL;

use ProcessWire\Field;
use ProcessWire\Template;

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
  * @return \ProcessWire\Templates The ProcessWire $templates API variable.
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
  * @return \ProcessWire\Session The ProcessWire $session variable.
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
   * Shortcut for wire('database')
   * @return ProcessWire\Database The ProcessWire $database API variable.
   */
  public static function database()
  {
    return self::wire('database');
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
   * Matches a ProcessWire Fieldtype to corresponding GraphQL Field
   * @param Field $field A Processwire field.
   * @return mixed|null Returns a GraphQL compatible field or null if match is not found.
   */
  public static function pwFieldToGraphqlClass(Field $field)
  {

    
    // use local field if available
    $className = "\\ProcessWire\\GraphQL\\Type\\Fieldtype\\" . $field->type->className();
    if (class_exists($className)) {
      return $className;
    }

    // use third party field if available
    $thirdPartyFieldtypeClassName = "ProcessWire\\GraphQL\\Type\\Fieldtype\\FieldtypeThirdParty";
    if (class_exists($thirdPartyFieldtypeClassName::getThirdPartyClassName($field))) {
      return $thirdPartyFieldtypeClassName;
    }

    return null;
  }

  /**
   * Used for circular dependant types. Until the type is built in full this
   * placeholder is used. See Cache::type() for an example.
   */
  public static function placeholder()
  {
    return 'placeholder';
  }

  public static function normalizeFieldName($name)
  {
    return Utils::sanitizer()->camelCase($name);
  }

  public static function normalizeTypeName($name)
  {
    return ucfirst(Utils::normalizeFieldName($name));
  }

  /**
   * Checks if the template is a repeater template
   * @param Template $template
   * @return boolean True if the template is repeater template, false otherwise.
   */
  public static function isRepeaterTemplate(Template $template)
  {
    // if it's not prefixed with "repeater_" then it's not a repeater template
    if (strpos($template->name, 'repeater_') !== 0) {
      return false;
    }

    // if it's not flagged as system then it's not repeater
    if (!($template->flags & Template::flagSystem)) {
      return false;
    }

    return true;
  }
}

function log($data, $label = '')
{
  echo "\n======================================\n";
  if ($label) {
    echo "$label\n";
  }
  echo $data;
  echo "\n======================================\n";  
}

function logArr($data, $label = '')
{
  log(json_encode($data, JSON_PRETTY_PRINT), $label);
}