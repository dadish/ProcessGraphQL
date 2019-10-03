<?php namespace ProcessWire\GraphQL;


$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$config = ProcessWire::buildConfig($pwDir, null, [
  "siteDir" => "site-default"
]);

require_once realpath(__DIR__ . "/databaseReset.php");