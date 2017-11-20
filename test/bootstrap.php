<?php namespace ProcessWire\GraphQL;

// paths
$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");
$siteDir = realpath($pwDir . "/site-default/");
$moduleDir = $siteDir . "/modules/ProcessGraphQL";

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

// overwrite site-default's config.php with our own custom one
copy(__DIR__ . "/pw-config.php", $siteDir . "/config.php");

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


// symlink our module inside the site's modules
// directory, so we can install it as a module to
// our processwire instance
if (!file_exists($moduleDir)) {
  \symlink($baseDir, $moduleDir);
}

// install ProcessGraphQL module
$modules = \ProcessWire\wire('modules');
if ($modules->isInstalled('ProcessGraphQL')) {
  echo "ProcessGraphQL is already installed!\n\n";
} else {
  echo "Installing ProcessGraphQL...\n";
  $modules->refresh();
  $module = $modules->install('ProcessGraphQL');
  if ($module && $module->className() === 'ProcessGraphQL') {
    echo "ProcessGraphQL installed!\n\n";
  } else {
    echo "Could not install ProcessGraphQL\n\n";
  }
}