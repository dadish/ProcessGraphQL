<?php

$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$wireConfig = ProcessWire::buildConfig($pwDir);
$wire = new ProcessWire($wireConfig);

// =====================
// Setup GraphiQL Assets
// =====================
$config = $wire->fuel('config');

$graphql = $wire->fuel('modules')->get('ProcessGraphQL');
$graphql->GraphQLServerUrl = 'http://127.0.0.1:8091';
$graphql->setupGraphiQLAssets();

$filename = realpath($baseDir . $_SERVER['SCRIPT_NAME']);
if ($filename === $baseDir) {
  require_once(realpath("$baseDir/graphiql/full.php"));
  return;
}

if (!file_exists($filename)) {
  http_response_code(404);
  echo "Not Found!";
  return;
}

if (preg_match('/^[a-zA-Z\/_.]+\.js$/', $filename)) {
  header('Content-type: text/javascript');
} else if (preg_match('/^[a-zA-Z\/_.]+\.css$/', $filename)) {
  header('Content-type: text/css');
}
require_once($filename);