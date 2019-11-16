<?php

$baseDir = realpath(__DIR__ . "/../");
$pwDir = realpath($baseDir . "/vendor/processwire/processwire/");

// load dependencies
require_once $baseDir . "/vendor/autoload.php";

use ProcessWire\ProcessWire;

$wireConfig = ProcessWire::buildConfig($pwDir, null, [
"siteDir" => "site-default"
]);
$wire = new ProcessWire($wireConfig);

// =====================
// Setup GraphiQL Assets
// =====================
$config = $wire->fuel('config');

$config->js('ProcessGraphQL', [
  'GraphQLServerUrl' => 'http://localhost:8081',
]);

$config->scripts->add("https://unpkg.com/es6-promise@4.2.8/dist/es6-promise.auto.min.js");
$config->scripts->add("https://unpkg.com/whatwg-fetch@3.0.0/dist/fetch.umd.js");
$config->scripts->add("https://unpkg.com/react@16.12.0/umd/react.production.min.js");
$config->scripts->add("https://unpkg.com/react-dom@16.12.0/umd/react-dom.production.min.js");
$config->scripts->add('https://unpkg.com/graphiql@0.14.2/graphiql.min.js');
$config->styles->add('https://unpkg.com/graphiql@0.14.2/graphiql.css');

$filename = realpath($baseDir . $_SERVER['SCRIPT_NAME']);
if ($filename === $baseDir) {
  require_once(realpath("$baseDir/GraphiQL/full.php"));
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