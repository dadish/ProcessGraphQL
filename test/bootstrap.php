<?php namespace ProcessWire\GraphQL;

// paths
$baseDir = __DIR__ . "/../";
$pwDir = $baseDir . "vendor/processwire/processwire/";
$siteDir = $pwDir . "site-default";

// load dependencies
require_once $baseDir . "vendor/autoload.php";

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
echo "Database setup finished\n\n";

// fire up ProcessWire!
new ProcessWire($config);