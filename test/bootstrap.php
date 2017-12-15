<?php namespace ProcessWire\GraphQL;

// start the PHP session
ob_start();

// paths
$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");
$siteDir = realpath($pwDir . "/site-default/");
$moduleDir = $siteDir . "/modules/ProcessGraphQL";
$testFilesDir = $baseDir . "/test/files";
$siteFilesDir = $siteDir . "/assets/files";

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

// remove install.php otherwise PW will think the site needs to be installed
$installFile = $pwDir . "/install.php";
if (file_exists($installFile)) {
	unlink($installFile);
}

// overwrite site-default's config.php with our own custom one
copy(__DIR__ . "/pw-config.php", $siteDir . "/config.php");

// create necessary asset dirs
$sessionsDir = $siteDir . "/assets/sessions";
if (!file_exists($sessionsDir)) {
	mkdir($sessionsDir);
}

// symlink our module inside the site's modules
// directory, so we can install it as a module to
// our processwire instance
if (!file_exists($moduleDir)) {
  \symlink($baseDir, $moduleDir);
}

// symlink skyscrapers pages files to site's asset files
if (!file_exists($siteFilesDir)) {
	\symlink($testFilesDir, $siteFilesDir);
}

use ProcessWire\ProcessWire;

$config = ProcessWire::buildConfig($pwDir, null, [
  "siteDir" => "site-default"
  ]);

// fill up database
echo "Database setup started...\n";
$dsn = "mysql:dbname=$config->dbName;host=$config->dbHost";
$sql = \file_get_contents(__DIR__ . "/skyscrapers.sql");
$pdo = new \PDO($dsn, $config->dbUser, $config->dbPass);
$pdo->exec($sql);
echo "Database setup finished.\n\n";

// Fire up ProcessWire!!!
new ProcessWire($config);
