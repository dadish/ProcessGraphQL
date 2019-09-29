<?php namespace ProcessWire\GraphQL;


$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$config = ProcessWire::buildConfig($pwDir, null, [
  "siteDir" => "site-default"
]);

// fill up database
echo "Database setup started...\n";
$dsn = "mysql:dbname=$config->dbName;host=$config->dbHost";
$sql = \file_get_contents(__DIR__ . "/skyscrapers.sql");
$pdo = new \PDO($dsn, $config->dbUser, $config->dbPass, []);
$pdo->exec($sql);
echo "Database setup finished.\n\n";
