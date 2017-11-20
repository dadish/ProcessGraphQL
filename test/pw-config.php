<?php namespace ProcessWire;

if(!defined("PROCESSWIRE")) die();

/*** 1. SYSTEM MODES ****************************************************************************/

/**
 * Enable debug mode?
 * 
 * Debug mode causes additional info to appear for use during site development and debugging. 
 * This is almost always recommended for sites in development. However, you should
 * always have this disabled for live/production sites since it reveals more information
 * than is advisible for security. 
 * 
 * You may also set this to the constant `Config::debugVerbose` to enable verbose debug mode,
 * which uses more memory and time. 
 * 
 * #notes This enables debug mode for ALL requests. See the debugIf option for an alternative.
 * 
 * @var bool
 *
 */
$config->debug = true;

/**
 * Enable ProcessWire advanced development mode?
 * 
 * Turns on additional options in ProcessWire Admin that aren't applicable in all instances.
 * Be careful with this as some options configured in advanced mode cannot be removed once
 * set (at least not without going directly into the database). 
 * 
 * #notes Recommended mode is false, except occasionally during ProcessWire core or module development.
 * @var bool
 *
 */
$config->advanced = true;

/**
 * Enable core API variables to be accessed as function calls?
 * 
 * Benefits are better type hinting, always in scope, and potentially shorter API calls.
 * See the file /wire/core/FunctionsAPI.php for details on these functions.
 * 
 * @var bool
 * 
 */
$config->useFunctionsAPI = true;




/*** 7. DATABASE ********************************************************************************/

/**
 * Database character set
 * 
 * utf8 is the only recommended value for this. 
 *
 * Note that you should probably not add/change this on an existing site. i.e. don't add this to 
 * an existing ProcessWire installation without asking how in the ProcessWire forums. 
 *
 */
$config->dbCharset = 'utf8';

/**
 * Database engine
 * 
 * May be 'InnoDB' or 'MyISAM'. Avoid changing this after install.
 * 
 */
$config->dbEngine = 'MyISAM';

/**
 * Allow MySQL query caching?
 * 
 * Set to false to to disable query caching. This will make everything run slower so should
 * only used for DB debugging purposes.
 * 
 * @var bool
 *
 */
$config->dbCache = true;

/**
 * MySQL database exec path
 * 
 * Path to mysql/mysqldump commands on the file system
 *
 * This enables faster backups and imports when available.
 *
 * Example: /usr/bin/
 * Example: /Applications/MAMP/Library/bin/
 * 
 * @param string
 *
 */
$config->dbPath = '';

/**
 * Force lowercase tables?
 * 
 * Force any created field_* tables to be lowercase.
 * Recommend value is true except for existing installations that already have mixed case tables.
 * 
 */
$config->dbLowercaseTables = true;

/**
 * Database username
 * 
 */
$config->dbUser = 'pwql_user';

/**
 * Database password
 * 
 */
$config->dbPass = 'pwql_password';

/**
 * Database host
 * 
 */
$config->dbHost = 'localhost';

/**
 * Database port
 * 
 */
$config->dbPort = 3306;

/**
 * Database init command (PDO::MYSQL_ATTR_INIT_COMMAND)
 *
 * Note: Placeholder "{charset}" gets automatically replaced with $config->dbCharset.
 * 
 * @var string
 *
 */
$config->dbInitCommand = "SET NAMES '{charset}'";

/**
 * Set or adjust SQL mode per MySQL version
 * 
 * Array indexes are minimum MySQL version mode applies to. Array values are 
 * the names of the mode(s) to apply. If value is preceded with "remove:" the mode will 
 * be removed, or if preceded with "add:" the mode will be added. If neither is present 
 * then the mode will be set exactly as given. To specify more than one SQL mode for the
 * value, separate them by commas (CSV). To specify multiple statements for the same 
 * version, separate them with a slash "/".
 * 
 * ~~~~~
 * array("5.7.0" => "remove:STRICT_TRANS_TABLES,ONLY_FULL_GROUP_BY/add:NO_ZERO_DATE")
 * ~~~~~
 * 
 * @var array
 * 
 */
$config->dbSqlModes = array(
	"5.7.0" => "remove:STRICT_TRANS_TABLES,ONLY_FULL_GROUP_BY"
);

/**
 * A key=>value array of any additional driver-specific connection options.
 * 
 * @var array
 * 
 */
$config->dbOptions = array();

/**
 * Optional DB socket config for sites that need it (for most you should exclude this)
 * 
 * @var string
 *
 */
$config->dbSocket = '';

/**
 * Maximum number of queries WireDatabasePDO will log in memory (when $config->debug is enabled)
 * 
 * @var int
 * 
 */
$config->dbQueryLogMax = 500;

/**
 * Remove 4-byte characters (like emoji) when dbEngine is not utf8mb4?
 * 
 * When charset is not “utf8mb4” and this value is true, 4-byte UTF-8 characters are stripped
 * out of inserted values when possible. Note that this can add some overhead to INSERTs. 
 * 
 * @var bool
 * 
 */
$config->dbStripMB4 = false;

/**
 * Allow Exceptions to propagate?
 * 
 * When true, ProcessWire will not capture Exceptions and will instead let them fall
 * through in their original state. Use only if you are running ProcessWire with your
 * own Exception handler. Most installations should leave this at false.
 * 
 * @var bool
 * 
 */
$config->allowExceptions = false;